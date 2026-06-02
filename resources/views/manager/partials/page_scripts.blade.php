    <script>
        (function () {
            const modalTriggers = document.querySelectorAll('.js-open-modal');
            const closeButtons = document.querySelectorAll('.js-close-modal');
            const modals = document.querySelectorAll('.modal-shell');
            let pendingDeleteForm = null;
            let pendingDeleteTableForm = null;
            const tableQrState = {
                name: '',
                url: '',
            };
            const cropState = {
                image: null,
                box: null,
                img: null,
                zoomInput: null,
                hiddenInput: null,
                empty: null,
                baseWidth: 0,
                baseHeight: 0,
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                startOffsetX: 0,
                startOffsetY: 0,
                isDragging: false,
            };
            const editCropState = {
                image: null,
                box: null,
                img: null,
                zoomInput: null,
                hiddenInput: null,
                empty: null,
                baseWidth: 0,
                baseHeight: 0,
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                startOffsetX: 0,
                startOffsetY: 0,
                isDragging: false,
            };

            function prepareCreateMenuModal(trigger) {
                if (trigger.dataset.modal !== 'create-menu') {
                    return;
                }

                const category = trigger.dataset.category || 'Makanan';
                const title = document.getElementById('modalCreateMenuTitle');
                const subtitle = document.getElementById('modalCreateMenuSubtitle');
                const categoryInput = document.getElementById('createMenuCategory');
                const nameInput = document.getElementById('createMenuName');

                document.querySelectorAll('.js-menu-category-label').forEach((label) => {
                    label.textContent = category;
                });

                if (title) {
                    title.textContent = 'Tambah ' + category;
                }

                if (subtitle) {
                    subtitle.textContent = 'Tambahkan ' + category.toLowerCase() + ' baru ke SwiftBite Morning Bakery.';
                }

                if (categoryInput) {
                    categoryInput.value = category;
                }

                if (nameInput) {
                    nameInput.placeholder = category === 'Minuman' ? 'Contoh: Air Putih, maks. 20 karakter' : 'Contoh: Croissant, maks. 20 karakter';
                }
            }

            function prepareEditMenuModal(trigger) {
                if (trigger.dataset.modal !== 'edit-menu') {
                    return;
                }

                const form = document.querySelector('.js-menu-edit-form');
                const nameInput = document.getElementById('editMenuName');
                const priceInput = document.getElementById('editMenuPrice');
                const statusInput = document.getElementById('editMenuStatus');
                const fileInput = document.querySelector('.js-edit-crop-input');

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (nameInput) {
                    nameInput.value = trigger.dataset.name || '';
                }

                if (priceInput) {
                    priceInput.value = trigger.dataset.price || '';
                }

                if (statusInput) {
                    statusInput.value = trigger.dataset.status || 'tersedia';
                }

                if (fileInput) {
                    fileInput.value = '';
                }

                resetEditCropPreview(trigger.dataset.photo || '');
            }

            function prepareStockMenuModal(trigger) {
                if (trigger.dataset.modal !== 'stock-menu') {
                    return;
                }

                const form = document.querySelector('.js-stock-form');
                const title = document.getElementById('modalStockMenuTitle');
                const subtitle = document.querySelector('.js-stock-menu-subtitle');
                const productName = document.querySelector('.js-stock-product-name');
                const currentStock = document.querySelector('.js-stock-current');
                const amountInput = document.getElementById('stockAmountInput');
                const addInput = document.getElementById('stockChangeAdd');
                const noteInput = document.getElementById('stockNote');
                const name = trigger.dataset.name || 'Produk';
                const stock = trigger.dataset.stock || '0';

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (title) {
                    title.textContent = 'Kelola Stok';
                }

                if (subtitle) {
                    subtitle.textContent = 'Perbarui stok untuk ' + name + '.';
                }

                if (productName) {
                    productName.textContent = name;
                }

                if (currentStock) {
                    currentStock.textContent = stock;
                }

                if (amountInput) {
                    amountInput.value = '';
                    amountInput.max = '999';
                }

                if (addInput) {
                    addInput.checked = true;
                }

                if (noteInput) {
                    noteInput.value = '';
                }
            }

            function prepareEditPackageModal(trigger) {
                if (trigger.dataset.modal !== 'edit-package') {
                    return;
                }

                const form = document.querySelector('.js-package-edit-form');
                const nameInput = document.querySelector('.js-edit-package-name');
                const priceInput = document.querySelector('.js-edit-package-price');
                const statusInput = document.querySelector('.js-edit-package-status');
                const fileInput = document.querySelector('.js-edit-package-photo');
                let items = {};

                try {
                    items = JSON.parse(trigger.dataset.items || '{}') || {};
                } catch (error) {
                    items = {};
                }

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (nameInput) {
                    nameInput.value = trigger.dataset.name || '';
                }

                if (priceInput) {
                    priceInput.value = trigger.dataset.price || '';
                }

                if (statusInput) {
                    statusInput.value = trigger.dataset.status || 'tersedia';
                }

                if (fileInput) {
                    fileInput.value = '';
                }

                document.querySelectorAll('.js-edit-package-qty').forEach((input) => {
                    input.value = items[input.dataset.menuId] || 0;
                });
            }

            function prepareEditTableModal(trigger) {
                if (trigger.dataset.modal !== 'edit-table') {
                    return;
                }

                const form = document.querySelector('.js-table-edit-form');
                const nameInput = document.getElementById('editTableName');
                const statusInput = document.getElementById('editTableStatus');

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (nameInput) {
                    nameInput.value = trigger.dataset.name || '';
                }

                if (statusInput) {
                    statusInput.value = trigger.dataset.status || 'aktif';
                }
            }

            function prepareTableQrModal(trigger) {
                if (trigger.dataset.modal !== 'table-qr') {
                    return;
                }

                const title = document.getElementById('modalTableQrTitle');
                const subtitle = document.querySelector('.js-table-qr-subtitle');
                const preview = document.querySelector('.js-table-qr-preview');
                const urlText = document.querySelector('.js-table-qr-url');
                const card = trigger.closest('.table-card-item');
                const qrBox = card ? card.querySelector('.qr-box') : null;
                const name = trigger.dataset.name || 'Meja';
                const url = trigger.dataset.url || '#';
                tableQrState.name = name;
                tableQrState.url = url;

                if (title) {
                    title.textContent = 'QR ' + name;
                }

                if (subtitle) {
                    subtitle.textContent = 'Scan QR untuk langsung membuka menu customer ' + name + '.';
                }

                if (preview) {
                    preview.innerHTML = qrBox ? qrBox.innerHTML : '';
                }

                if (urlText) {
                    urlText.textContent = url;
                }

            }

            function tableQrFileName() {
                const baseName = (tableQrState.name || 'meja')
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-|-$/g, '') || 'meja';

                return 'swiftbite-' + baseName + '-qr.png';
            }

            function getTableQrSvg() {
                const preview = document.querySelector('.js-table-qr-preview');
                return preview ? preview.querySelector('svg') : null;
            }

            function escapeHtml(value) {
                const wrapper = document.createElement('div');
                wrapper.textContent = value || '';
                return wrapper.innerHTML;
            }

            function downloadTableQrPng() {
                const svg = getTableQrSvg();

                if (!svg) {
                    return;
                }

                const serializer = new XMLSerializer();
                const svgText = serializer.serializeToString(svg);
                const svgBlob = new Blob([svgText], { type: 'image/svg+xml;charset=utf-8' });
                const url = URL.createObjectURL(svgBlob);
                const image = new Image();

                image.onload = () => {
                    const size = 900;
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    canvas.width = size;
                    canvas.height = size;
                    context.fillStyle = '#ffffff';
                    context.fillRect(0, 0, size, size);
                    context.drawImage(image, 40, 40, size - 80, size - 80);
                    URL.revokeObjectURL(url);

                    const link = document.createElement('a');
                    link.download = tableQrFileName();
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                };

                image.src = url;
            }

            function printTableQr() {
                const svg = getTableQrSvg();

                if (!svg) {
                    return;
                }

                const name = tableQrState.name || 'Meja';
                const url = tableQrState.url || '';
                const svgText = new XMLSerializer().serializeToString(svg);
                const printWindow = window.open('', '_blank', 'width=520,height=720');
                const safeName = escapeHtml(name);
                const safeUrl = escapeHtml(url);

                if (!printWindow) {
                    return;
                }

                printWindow.document.write(`
                    <!doctype html>
                    <html lang="id">
                    <head>
                        <meta charset="utf-8">
                        <title>QR ${safeName}</title>
                        <style>
                            body { margin: 0; font-family: Arial, sans-serif; color: #2a1309; }
                            .sheet { min-height: 100vh; display: grid; place-items: center; padding: 32px; box-sizing: border-box; }
                            .card { width: 100%; max-width: 360px; text-align: center; border: 2px solid #5a2b17; border-radius: 16px; padding: 28px; }
                            .brand { font-size: 18px; font-weight: 800; margin-bottom: 8px; }
                            h1 { font-size: 34px; margin: 0 0 18px; }
                            svg { width: 260px; height: 260px; }
                            .url { margin-top: 18px; font-size: 12px; overflow-wrap: anywhere; }
                            @media print { .sheet { padding: 0; } .card { border-color: #000; } }
                        </style>
                    </head>
                    <body>
                        <main class="sheet">
                            <section class="card">
                                <div class="brand">SwiftBite Morning Bakery</div>
                                <h1>${safeName}</h1>
                                ${svgText}
                                <div class="url">${safeUrl}</div>
                            </section>
                        </main>
                    </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.focus();
                setTimeout(() => printWindow.print(), 250);
            }

            function updateCropImage() {
                if (!cropState.image || !cropState.box || !cropState.img || !cropState.zoomInput) {
                    return;
                }

                const zoom = Number(cropState.zoomInput.value || 1);
                const boxWidth = cropState.box.clientWidth;
                const boxHeight = cropState.box.clientHeight;
                const width = cropState.baseWidth * zoom;
                const height = cropState.baseHeight * zoom;
                const maxOffsetX = Math.max(0, (width - boxWidth) / 2);
                const maxOffsetY = Math.max(0, (height - boxHeight) / 2);

                cropState.offsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, cropState.offsetX));
                cropState.offsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, cropState.offsetY));
                cropState.img.style.width = cropState.baseWidth + 'px';
                cropState.img.style.height = cropState.baseHeight + 'px';
                cropState.img.style.transform = 'translate(calc(-50% + ' + cropState.offsetX + 'px), calc(-50% + ' + cropState.offsetY + 'px)) scale(' + zoom + ')';
            }

            function setCropImage(file) {
                if (!file || !cropState.img || !cropState.box || !cropState.zoomInput) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = () => {
                    const image = new Image();

                    image.onload = () => {
                        const boxWidth = cropState.box.clientWidth;
                        const boxHeight = cropState.box.clientHeight;
                        const coverScale = Math.max(boxWidth / image.naturalWidth, boxHeight / image.naturalHeight);

                        cropState.image = image;
                        cropState.baseWidth = image.naturalWidth * coverScale;
                        cropState.baseHeight = image.naturalHeight * coverScale;
                        cropState.offsetX = 0;
                        cropState.offsetY = 0;
                        cropState.zoomInput.value = '1';
                        cropState.zoomInput.disabled = false;
                        cropState.img.src = reader.result;
                        cropState.img.style.display = 'block';

                        if (cropState.empty) {
                            cropState.empty.style.display = 'none';
                        }

                        updateCropImage();
                    };

                    image.src = reader.result;
                };

                reader.readAsDataURL(file);
            }

            function writeCroppedImage() {
                if (!cropState.image || !cropState.box || !cropState.hiddenInput || !cropState.zoomInput) {
                    return;
                }

                const canvas = document.createElement('canvas');
                const boxWidth = cropState.box.clientWidth;
                const boxHeight = cropState.box.clientHeight;
                const zoom = Number(cropState.zoomInput.value || 1);
                const scale = (cropState.baseWidth * zoom) / cropState.image.naturalWidth;
                const imageLeft = (boxWidth - cropState.baseWidth * zoom) / 2 + cropState.offsetX;
                const imageTop = (boxHeight - cropState.baseHeight * zoom) / 2 + cropState.offsetY;
                const sourceX = Math.max(0, -imageLeft / scale);
                const sourceY = Math.max(0, -imageTop / scale);
                const sourceWidth = Math.min(cropState.image.naturalWidth - sourceX, boxWidth / scale);
                const sourceHeight = Math.min(cropState.image.naturalHeight - sourceY, boxHeight / scale);
                const outputWidth = 800;
                const outputHeight = 500;

                canvas.width = outputWidth;
                canvas.height = outputHeight;
                canvas.getContext('2d').drawImage(cropState.image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, outputWidth, outputHeight);
                cropState.hiddenInput.value = canvas.toDataURL('image/jpeg', .9);
            }

            function initMenuCropper() {
                cropState.box = document.querySelector('.js-crop-box');
                cropState.img = document.querySelector('.js-crop-image');
                cropState.zoomInput = document.querySelector('.js-crop-zoom');
                cropState.hiddenInput = document.querySelector('.js-cropped-image');
                cropState.empty = document.querySelector('.js-crop-empty');

                const fileInput = document.querySelector('.js-crop-input');
                const form = document.querySelector('.js-menu-create-form');

                if (!cropState.box || !cropState.img || !fileInput || !form) {
                    return;
                }

                fileInput.addEventListener('change', () => {
                    setCropImage(fileInput.files ? fileInput.files[0] : null);
                });

                cropState.zoomInput?.addEventListener('input', updateCropImage);

                cropState.box.addEventListener('pointerdown', (event) => {
                    if (!cropState.image || event.button !== 0) {
                        return;
                    }

                    cropState.isDragging = true;
                    cropState.startX = event.clientX;
                    cropState.startY = event.clientY;
                    cropState.startOffsetX = cropState.offsetX;
                    cropState.startOffsetY = cropState.offsetY;
                    cropState.box.classList.add('is-dragging');
                    cropState.box.setPointerCapture(event.pointerId);
                });

                cropState.box.addEventListener('pointermove', (event) => {
                    if (!cropState.isDragging) {
                        return;
                    }

                    event.preventDefault();
                    cropState.offsetX = cropState.startOffsetX + event.clientX - cropState.startX;
                    cropState.offsetY = cropState.startOffsetY + event.clientY - cropState.startY;
                    updateCropImage();
                });

                function stopCropDrag() {
                    cropState.isDragging = false;
                    cropState.box.classList.remove('is-dragging');
                }

                cropState.box.addEventListener('pointerup', stopCropDrag);
                cropState.box.addEventListener('pointercancel', stopCropDrag);
                form.addEventListener('submit', writeCroppedImage);
            }

            function resetEditCropPreview(photoUrl) {
                editCropState.image = null;
                editCropState.offsetX = 0;
                editCropState.offsetY = 0;

                if (editCropState.hiddenInput) {
                    editCropState.hiddenInput.value = '';
                }

                if (editCropState.zoomInput) {
                    editCropState.zoomInput.value = '1';
                    editCropState.zoomInput.disabled = true;
                }

                if (editCropState.img && photoUrl) {
                    editCropState.img.src = photoUrl;
                    editCropState.img.style.display = 'block';
                    editCropState.img.style.width = '100%';
                    editCropState.img.style.height = '100%';
                    editCropState.img.style.objectFit = 'cover';
                    editCropState.img.style.transform = 'none';
                    editCropState.img.style.left = '0';
                    editCropState.img.style.top = '0';
                } else if (editCropState.img) {
                    editCropState.img.removeAttribute('src');
                    editCropState.img.style.display = 'none';
                }

                if (editCropState.empty) {
                    editCropState.empty.style.display = photoUrl ? 'none' : 'grid';
                }
            }

            function updateEditCropImage() {
                if (!editCropState.image || !editCropState.box || !editCropState.img || !editCropState.zoomInput) {
                    return;
                }

                const zoom = Number(editCropState.zoomInput.value || 1);
                const boxWidth = editCropState.box.clientWidth;
                const boxHeight = editCropState.box.clientHeight;
                const width = editCropState.baseWidth * zoom;
                const height = editCropState.baseHeight * zoom;
                const maxOffsetX = Math.max(0, (width - boxWidth) / 2);
                const maxOffsetY = Math.max(0, (height - boxHeight) / 2);

                editCropState.offsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, editCropState.offsetX));
                editCropState.offsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, editCropState.offsetY));
                editCropState.img.style.left = '50%';
                editCropState.img.style.top = '50%';
                editCropState.img.style.objectFit = '';
                editCropState.img.style.width = editCropState.baseWidth + 'px';
                editCropState.img.style.height = editCropState.baseHeight + 'px';
                editCropState.img.style.transform = 'translate(calc(-50% + ' + editCropState.offsetX + 'px), calc(-50% + ' + editCropState.offsetY + 'px)) scale(' + zoom + ')';
            }

            function setEditCropImage(file) {
                if (!file || !editCropState.img || !editCropState.box || !editCropState.zoomInput) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = () => {
                    const image = new Image();

                    image.onload = () => {
                        const boxWidth = editCropState.box.clientWidth;
                        const boxHeight = editCropState.box.clientHeight;
                        const coverScale = Math.max(boxWidth / image.naturalWidth, boxHeight / image.naturalHeight);

                        editCropState.image = image;
                        editCropState.baseWidth = image.naturalWidth * coverScale;
                        editCropState.baseHeight = image.naturalHeight * coverScale;
                        editCropState.offsetX = 0;
                        editCropState.offsetY = 0;
                        editCropState.zoomInput.value = '1';
                        editCropState.zoomInput.disabled = false;
                        editCropState.img.src = reader.result;
                        editCropState.img.style.display = 'block';

                        if (editCropState.empty) {
                            editCropState.empty.style.display = 'none';
                        }

                        updateEditCropImage();
                    };

                    image.src = reader.result;
                };

                reader.readAsDataURL(file);
            }

            function writeEditCroppedImage() {
                if (!editCropState.image || !editCropState.box || !editCropState.hiddenInput || !editCropState.zoomInput) {
                    return;
                }

                const canvas = document.createElement('canvas');
                const boxWidth = editCropState.box.clientWidth;
                const boxHeight = editCropState.box.clientHeight;
                const zoom = Number(editCropState.zoomInput.value || 1);
                const scale = (editCropState.baseWidth * zoom) / editCropState.image.naturalWidth;
                const imageLeft = (boxWidth - editCropState.baseWidth * zoom) / 2 + editCropState.offsetX;
                const imageTop = (boxHeight - editCropState.baseHeight * zoom) / 2 + editCropState.offsetY;
                const sourceX = Math.max(0, -imageLeft / scale);
                const sourceY = Math.max(0, -imageTop / scale);
                const sourceWidth = Math.min(editCropState.image.naturalWidth - sourceX, boxWidth / scale);
                const sourceHeight = Math.min(editCropState.image.naturalHeight - sourceY, boxHeight / scale);

                canvas.width = 800;
                canvas.height = 500;
                canvas.getContext('2d').drawImage(editCropState.image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, 800, 500);
                editCropState.hiddenInput.value = canvas.toDataURL('image/jpeg', .9);
            }

            function initEditMenuCropper() {
                editCropState.box = document.querySelector('.js-edit-crop-box');
                editCropState.img = document.querySelector('.js-edit-crop-image');
                editCropState.zoomInput = document.querySelector('.js-edit-crop-zoom');
                editCropState.hiddenInput = document.querySelector('.js-edit-cropped-image');
                editCropState.empty = document.querySelector('.js-edit-crop-empty');

                const fileInput = document.querySelector('.js-edit-crop-input');
                const form = document.querySelector('.js-menu-edit-form');

                if (!editCropState.box || !editCropState.img || !fileInput || !form) {
                    return;
                }

                fileInput.addEventListener('change', () => {
                    setEditCropImage(fileInput.files ? fileInput.files[0] : null);
                });

                editCropState.zoomInput?.addEventListener('input', updateEditCropImage);

                editCropState.box.addEventListener('pointerdown', (event) => {
                    if (!editCropState.image || event.button !== 0) {
                        return;
                    }

                    editCropState.isDragging = true;
                    editCropState.startX = event.clientX;
                    editCropState.startY = event.clientY;
                    editCropState.startOffsetX = editCropState.offsetX;
                    editCropState.startOffsetY = editCropState.offsetY;
                    editCropState.box.classList.add('is-dragging');
                    editCropState.box.setPointerCapture(event.pointerId);
                });

                editCropState.box.addEventListener('pointermove', (event) => {
                    if (!editCropState.isDragging) {
                        return;
                    }

                    event.preventDefault();
                    editCropState.offsetX = editCropState.startOffsetX + event.clientX - editCropState.startX;
                    editCropState.offsetY = editCropState.startOffsetY + event.clientY - editCropState.startY;
                    updateEditCropImage();
                });

                function stopEditCropDrag() {
                    editCropState.isDragging = false;
                    editCropState.box.classList.remove('is-dragging');
                }

                editCropState.box.addEventListener('pointerup', stopEditCropDrag);
                editCropState.box.addEventListener('pointercancel', stopEditCropDrag);
                form.addEventListener('submit', writeEditCroppedImage);
            }

            function openModal(id) {
                const modal = document.getElementById('modal-' + id);

                if (!modal) {
                    return;
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');

                if (!document.querySelector('.modal-shell.is-open')) {
                    document.body.style.overflow = '';
                }
            }

            modalTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    prepareCreateMenuModal(trigger);
                    prepareEditMenuModal(trigger);
                    prepareEditPackageModal(trigger);
                    prepareStockMenuModal(trigger);
                    prepareEditTableModal(trigger);
                    prepareTableQrModal(trigger);
                    openModal(trigger.dataset.modal);
                });
            });

            document.querySelector('.js-download-table-qr')?.addEventListener('click', downloadTableQrPng);
            document.querySelector('.js-print-table-qr')?.addEventListener('click', printTableQr);

            document.querySelectorAll('.success-banner, .error-banner').forEach((banner) => {
                banner.addEventListener('click', () => {
                    banner.classList.add('is-hidden');
                    setTimeout(() => {
                        banner.remove();
                    }, 220);
                });
            });

            document.querySelectorAll('.js-table-more').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const wrap = button.closest('.table-more-wrap');

                    document.querySelectorAll('.table-more-wrap.is-open').forEach((opened) => {
                        if (opened !== wrap) {
                            opened.classList.remove('is-open');
                        }
                    });

                    wrap?.classList.toggle('is-open');
                });
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('.table-more-wrap.is-open').forEach((wrap) => {
                    wrap.classList.remove('is-open');
                });
            });

            document.querySelectorAll('.js-delete-table-form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    pendingDeleteTableForm = form;

                    const name = document.querySelector('.js-delete-table-name');

                    if (name) {
                        name.textContent = form.dataset.tableName || 'Meja';
                    }

                    openModal('delete-table');
                });
            });

            document.querySelectorAll('.js-confirm-delete-table').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!pendingDeleteTableForm) {
                        return;
                    }

                    const form = pendingDeleteTableForm;
                    pendingDeleteTableForm = null;
                    form.submit();
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.modal-shell');

                    if (modal) {
                        closeModal(modal);
                    }
                });
            });

            modals.forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            document.querySelectorAll('.js-menu-scroll').forEach((button) => {
                button.addEventListener('click', () => {
                    const carousel = button.closest('.menu-carousel');
                    const rail = carousel ? carousel.querySelector('.menu-rail') : null;

                    if (!rail) {
                        return;
                    }

                    const direction = Number(button.dataset.direction || 1);
                    const firstCard = rail.querySelector('.menu-card');
                    const step = firstCard ? (firstCard.offsetWidth + 14) * 2 : 520;

                    rail.scrollBy({
                        left: direction * step,
                        behavior: 'smooth',
                    });
                });
            });

            document.querySelectorAll('.menu-rail').forEach((rail) => {
                let isDragging = false;
                let startX = 0;
                let startScrollLeft = 0;

                function stopDrag() {
                    if (!isDragging) {
                        return;
                    }

                    isDragging = false;
                    rail.classList.remove('is-dragging');
                }

                rail.addEventListener('pointerdown', (event) => {
                    if (event.button !== 0 || event.target.closest('button, a, input, select, textarea')) {
                        return;
                    }

                    isDragging = true;
                    startX = event.clientX;
                    startScrollLeft = rail.scrollLeft;
                    rail.classList.add('is-dragging');
                    rail.setPointerCapture(event.pointerId);
                });

                rail.addEventListener('pointermove', (event) => {
                    if (!isDragging) {
                        return;
                    }

                    event.preventDefault();
                    const walk = event.clientX - startX;
                    rail.scrollLeft = startScrollLeft - walk;
                });

                rail.addEventListener('pointerup', stopDrag);
                rail.addEventListener('pointercancel', stopDrag);
                rail.addEventListener('pointerleave', stopDrag);
            });

            function updateBulkToolbar(section) {
                const selected = section.querySelectorAll('.js-menu-select:checked');
                const toolbar = section.querySelector('.bulk-toolbar');
                const count = section.querySelector('.js-selected-count');

                section.querySelectorAll('.menu-card').forEach((card) => {
                    const checkbox = card.querySelector('.js-menu-select');
                    card.classList.toggle('is-selected', Boolean(checkbox && checkbox.checked));
                });

                if (count) {
                    count.textContent = selected.length;
                }

                if (toolbar) {
                    toolbar.classList.toggle('is-visible', selected.length > 0);
                }
            }

            function openDeleteMenuModal(form, cards) {
                const count = cards.length;
                const list = document.querySelector('.js-delete-menu-list');
                const moreInfo = document.querySelector('.js-delete-menu-more');

                pendingDeleteForm = form;

                document.querySelectorAll('.js-delete-menu-count').forEach((element) => {
                    element.textContent = count;
                });

                if (list) {
                    list.innerHTML = '';
                    cards.slice(0, 5).forEach((card) => {
                        const row = document.createElement('tr');

                        [card.dataset.menuName || '-', card.dataset.menuCategory || '-', card.dataset.menuPrice || '-'].forEach((value) => {
                            const cell = document.createElement('td');
                            cell.textContent = value;
                            row.appendChild(cell);
                        });

                        list.appendChild(row);
                    });
                }

                if (moreInfo) {
                    const remaining = Math.max(0, count - 5);
                    const text = moreInfo.querySelector('span');

                    moreInfo.style.display = remaining > 0 ? 'flex' : 'none';

                    if (text) {
                        text.textContent = '+' + remaining + ' menu lainnya akan ikut dihapus.';
                    }
                }

                openModal('confirm-delete-menu');
            }

            document.querySelectorAll('.section-card').forEach((section) => {
                const manageButton = section.querySelector('.js-toggle-menu-manage');
                const cancelButton = section.querySelector('.js-cancel-menu-manage');
                const form = section.querySelector('.js-bulk-delete-form');

                manageButton?.addEventListener('click', () => {
                    const isManaging = section.classList.toggle('is-managing');
                    manageButton.classList.toggle('is-active', isManaging);
                    manageButton.textContent = isManaging ? 'Selesai Kelola' : 'Kelola Menu';

                    if (!isManaging) {
                        section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                            checkbox.checked = false;
                        });
                    }

                    updateBulkToolbar(section);
                });

                cancelButton?.addEventListener('click', () => {
                    section.classList.remove('is-managing');
                    manageButton?.classList.remove('is-active');

                    if (manageButton) {
                        manageButton.textContent = 'Kelola Menu';
                    }

                    section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                        checkbox.checked = false;
                    });

                    updateBulkToolbar(section);
                });

                section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                    checkbox.addEventListener('change', () => updateBulkToolbar(section));
                });

                form?.addEventListener('submit', (event) => {
                    const selected = Array.from(section.querySelectorAll('.js-menu-select:checked'));

                    event.preventDefault();

                    if (selected.length === 0) {
                        return;
                    }

                    openDeleteMenuModal(form, selected.map((checkbox) => checkbox.closest('.menu-card')).filter(Boolean));
                });
            });

            document.querySelectorAll('.js-confirm-delete-menu').forEach((button) => {
                button.addEventListener('click', () => {
                    if (!pendingDeleteForm) {
                        return;
                    }

                    const form = pendingDeleteForm;
                    pendingDeleteForm = null;
                    form.submit();
                });
            });

            document.querySelectorAll('.js-single-delete-menu').forEach((button) => {
                button.addEventListener('click', () => {
                    const form = document.querySelector('.js-single-delete-form');
                    const input = document.querySelector('.js-single-delete-id');
                    const card = button.closest('.menu-card');

                    if (!form || !input || !card) {
                        return;
                    }

                    input.value = button.dataset.menuId || '';
                    openDeleteMenuModal(form, [card]);
                });
            });

            initMenuCropper();
            initEditMenuCropper();

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    const modal = document.querySelector('.modal-shell.is-open');

                    if (modal) {
                        closeModal(modal);
                    }
                }
            });

            @if ($section === 'users' && $errors->any())
                openModal(@json(old('modal_id', 'create-user')));
            @endif

            @if ($section === 'menus' && $errors->any())
                @if (old('modal_id') === 'create-package')
                    openModal('create-package');
                @elseif (old('modal_id') === 'edit-package')
                    openModal('edit-package');
                @else
                    prepareCreateMenuModal({ dataset: { modal: 'create-menu', category: @json(old('category', 'Makanan')) } });
                    openModal('create-menu');
                @endif
            @endif

            @if ($section === 'stock' && $errors->any())
                openModal('stock-menu');
            @endif

            @if ($section === 'tables' && $errors->any())
                openModal(@json(old('modal_id', 'create-table')));
            @endif
        })();
    </script>
