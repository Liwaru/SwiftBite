    <script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
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

            function prepareCreateMenuModal(trigger, keepSubmittedMode = false) {
                if (trigger.dataset.modal !== 'create-menu') {
                    return;
                }

                const category = trigger.dataset.category || 'Makanan';
                const title = document.getElementById('modalCreateMenuTitle');
                const subtitle = document.getElementById('modalCreateMenuSubtitle');
                const categoryInput = document.getElementById('createMenuCategory');
                const nameInput = document.getElementById('createMenuName');
                const barcodeInput = document.getElementById('createMenuBarcode');

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

                setCreateMenuInputMode(keepSubmittedMode && barcodeInput?.value.trim() ? 'barcode' : 'manual', !keepSubmittedMode);
            }

            function setCreateMenuInputMode(mode, clearBarcode = false) {
                const barcodeField = document.querySelector('.js-create-barcode-field');
                const barcodeInput = document.getElementById('createMenuBarcode');

                document.querySelectorAll('[data-create-menu-mode]').forEach((button) => {
                    button.classList.toggle('active', button.dataset.createMenuMode === mode);
                });

                if (barcodeField) {
                    barcodeField.hidden = mode !== 'barcode';
                }

                if (!barcodeInput) {
                    return;
                }

                if (mode === 'manual' && clearBarcode) {
                    barcodeInput.value = '';
                }

                if (mode === 'barcode') {
                    setTimeout(() => barcodeInput.focus(), 60);
                }
            }

            function prepareEditMenuModal(trigger) {
                if (trigger.dataset.modal !== 'edit-menu') {
                    return;
                }

                const form = document.querySelector('.js-menu-edit-form');
                const nameInput = document.getElementById('editMenuName');
                const descriptionInput = document.getElementById('editMenuDescription');
                const priceInput = document.getElementById('editMenuPrice');
                const statusInput = document.getElementById('editMenuStatus');
                const barcodeInput = document.getElementById('editMenuBarcode');
                const fileInput = document.querySelector('.js-edit-crop-input');

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (nameInput) {
                    nameInput.value = trigger.dataset.name || '';
                }

                if (descriptionInput) {
                    descriptionInput.value = trigger.dataset.description || '';
                }

                if (priceInput) {
                    priceInput.value = trigger.dataset.price || '';
                }

                if (statusInput) {
                    statusInput.value = trigger.dataset.status || 'tersedia';
                }

                if (barcodeInput) {
                    barcodeInput.value = trigger.dataset.barcode || '';
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
                const manualForm = document.querySelector('.js-stock-manual-form');
                const title = document.getElementById('modalStockMenuTitle');
                const subtitle = document.querySelector('.js-stock-menu-subtitle');
                const productName = document.querySelector('.js-stock-product-name');
                const currentStock = document.querySelector('.js-stock-current');
                const photoImage = document.querySelector('.js-stock-modal-image');
                const photoInitial = document.querySelector('.js-stock-modal-initial');
                const barcodeInput = document.getElementById('stockBarcodeInput');
                const scanStatus = document.querySelector('.js-stock-scan-status');
                const scanHistory = document.querySelector('.js-stock-scan-history');
                const amountInput = document.getElementById('stockAmountInput');
                const addInput = document.getElementById('stockChangeAdd');
                const noteInput = document.getElementById('stockNote');
                const name = trigger.dataset.name || 'Produk';
                const stock = trigger.dataset.stock || '0';

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

                if (form) {
                    form.dataset.stockMenuId = trigger.dataset.menuId || '';
                }

                if (manualForm) {
                    manualForm.action = trigger.dataset.action || '#';
                }

                if (photoImage && photoInitial) {
                    if (trigger.dataset.photo) {
                        photoImage.src = trigger.dataset.photo;
                        photoImage.hidden = false;
                        photoInitial.hidden = true;
                    } else {
                        photoImage.removeAttribute('src');
                        photoImage.hidden = true;
                        photoInitial.hidden = false;
                        photoInitial.textContent = trigger.dataset.initial || name.charAt(0).toUpperCase();
                    }
                }

                if (amountInput) {
                    amountInput.value = '';
                }

                if (addInput) {
                    addInput.checked = true;
                }

                if (noteInput) {
                    noteInput.value = '';
                }

                if (barcodeInput) {
                    barcodeInput.value = '';
                }

                if (scanStatus) {
                    scanStatus.querySelector('strong').textContent = 'Belum ada barcode discan.';
                    scanStatus.classList.remove('is-success', 'is-error');
                }

                if (scanHistory) {
                    scanHistory.innerHTML = '<tr class="stock-scan-empty"><td colspan="3">Belum ada scan.</td></tr>';
                }

                setStockInputMode('manual');
            }

            function setStockInputMode(mode) {
                document.querySelectorAll('[data-stock-mode]').forEach((button) => {
                    button.classList.toggle('active', button.dataset.stockMode === mode);
                });

                document.querySelectorAll('[data-stock-panel]').forEach((panel) => {
                    panel.hidden = panel.dataset.stockPanel !== mode;
                });

                if (mode === 'barcode') {
                    setTimeout(() => document.getElementById('stockBarcodeInput')?.focus(), 60);
                }
            }

            function initStockBarcodeScanner() {
                const form = document.querySelector('.js-stock-form');
                const barcodeInput = document.getElementById('stockBarcodeInput');
                const currentStock = document.querySelector('.js-stock-current');
                const productName = document.querySelector('.js-stock-product-name');
                const scanStatus = document.querySelector('.js-stock-scan-status');
                const scanHistory = document.querySelector('.js-stock-scan-history');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value || '';
                const scanUrl = @json(route('manager.stock.scan'));

                if (!form || !barcodeInput || !scanStatus || !scanHistory) {
                    return;
                }

                const setStatus = (message, type) => {
                    scanStatus.querySelector('strong').textContent = message;
                    scanStatus.classList.remove('is-success', 'is-error');

                    if (type) {
                        scanStatus.classList.add(type);
                    }
                };

                const escapeHtml = (value) => String(value || '').replace(/[&<>"']/g, (char) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                }[char]));

                const updateStockCard = (menu) => {
                    document.querySelectorAll('[data-stock-value="' + menu.id + '"]').forEach((element) => {
                        element.textContent = new Intl.NumberFormat('id-ID').format(menu.stock);
                    });

                    document.querySelectorAll('[data-stock-status="' + menu.id + '"]').forEach((element) => {
                        element.textContent = menu.status_label;
                        element.classList.remove('safe', 'low', 'empty');
                        element.classList.add(menu.status_class);
                    });

                    document.querySelectorAll('.js-stock-menu[data-menu-id="' + menu.id + '"]').forEach((button) => {
                        button.dataset.stock = menu.stock;
                    });
                };

                const addHistory = (menu) => {
                    scanHistory.querySelector('.stock-scan-empty')?.remove();

                    const row = document.createElement('tr');
                    row.className = 'stock-scan-row';
                    row.innerHTML = '<td>' + escapeHtml(menu.name) + '</td><td>+1</td><td>' + menu.stock + ' pcs</td>';
                    scanHistory.prepend(row);
                };

                barcodeInput.addEventListener('keydown', async (event) => {
                    if (event.key !== 'Enter') {
                        return;
                    }

                    event.preventDefault();

                    const barcode = barcodeInput.value.trim();

                    if (!barcode) {
                        return;
                    }

                    try {
                        const response = await fetch(scanUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({ barcode }),
                        });

                        const data = await response.json();

                        if (!response.ok || !data.ok) {
                            throw new Error(data.message || 'Barcode tidak ditemukan di data menu/produk.');
                        }

                        const menu = data.menu;
                        setStatus(data.message, 'is-success');

                        if (currentStock) {
                            currentStock.textContent = menu.stock;
                        }

                        if (productName) {
                            productName.textContent = menu.name;
                        }

                        updateStockCard(menu);
                        addHistory(menu);
                        barcodeInput.value = '';
                        barcodeInput.focus();
                    } catch (error) {
                        setStatus(error.message, 'is-error');
                        barcodeInput.select();
                    }
                });
            }

            function prepareEditPackageModal(trigger) {
                if (trigger.dataset.modal !== 'edit-package') {
                    return;
                }

                const form = document.querySelector('.js-package-edit-form');
                const nameInput = document.querySelector('.js-edit-package-name');
                const descriptionInput = document.querySelector('.js-edit-package-description');
                const priceInput = document.querySelector('.js-edit-package-price');
                const statusInput = document.querySelector('.js-edit-package-status');
                const permanentInput = document.querySelector('.js-edit-package-permanent');
                const startsAtInput = document.querySelector('.js-edit-package-starts-at');
                const endsAtInput = document.querySelector('.js-edit-package-ends-at');
                const fileInput = document.querySelector('.js-edit-package-photo');
                let items = {};
                let choices = {};

                try {
                    items = JSON.parse(trigger.dataset.items || '{}') || {};
                } catch (error) {
                    items = {};
                }

                try {
                    choices = JSON.parse(trigger.dataset.choices || '{}') || {};
                } catch (error) {
                    choices = {};
                }

                if (form) {
                    form.action = trigger.dataset.action || '#';
                }

                if (nameInput) {
                    nameInput.value = trigger.dataset.name || '';
                }

                if (descriptionInput) {
                    descriptionInput.value = trigger.dataset.description || '';
                }

                if (priceInput) {
                    priceInput.value = trigger.dataset.price || '';
                }

                if (statusInput) {
                    statusInput.value = trigger.dataset.status || 'tersedia';
                }

                if (startsAtInput) {
                    startsAtInput.value = trigger.dataset.startsAt || '';
                }

                if (endsAtInput) {
                    endsAtInput.value = trigger.dataset.endsAt || '';
                }

                if (permanentInput) {
                    permanentInput.checked = !trigger.dataset.startsAt && !trigger.dataset.endsAt;
                    syncPackagePeriod(permanentInput.closest('form'));
                }

                if (fileInput) {
                    fileInput.value = '';
                }

                document.querySelectorAll('.js-edit-package-qty').forEach((input) => {
                    input.value = items[input.dataset.menuId] || 0;
                });

                document.querySelectorAll('.js-edit-package-choice').forEach((input) => {
                    input.value = choices[input.dataset.category] || 0;
                });

                if (form) {
                    const builder = form.querySelector('.js-package-builder');

                    if (builder) {
                        syncPackageBuilder(builder);
                    }
                }
            }

            function selectedPackageItems(builder) {
                return Array.from(builder.querySelectorAll('.js-package-qty'))
                    .map((input) => ({
                        id: input.dataset.menuId,
                        name: input.dataset.menuName || 'Menu',
                        quantity: Math.max(0, Number(input.value || 0)),
                        input,
                    }))
                    .filter((item) => item.quantity > 0);
            }

            function syncPackageBuilder(builder) {
                const selected = selectedPackageItems(builder);
                const list = builder.querySelector('.js-package-selected-list');
                const count = builder.querySelector('.js-package-selected-count');

                builder.querySelectorAll('.package-picker-row').forEach((row) => {
                    const check = row.querySelector('.js-package-check');
                    const quantity = row.querySelector('.js-package-qty');
                    const isSelected = Number(quantity?.value || 0) > 0;

                    if (check) {
                        check.checked = isSelected;
                    }

                    row.classList.toggle('is-selected', isSelected);
                });

                if (count) {
                    count.textContent = selected.length + ' Menu';
                }

                if (!list) {
                    return;
                }

                list.innerHTML = '';

                if (selected.length === 0) {
                    const empty = document.createElement('span');
                    empty.className = 'package-selected-empty';
                    empty.textContent = 'Belum ada menu dipilih.';
                    list.appendChild(empty);
                    return;
                }

                selected.forEach((item) => {
                    const chip = document.createElement('span');
                    chip.className = 'package-chip';

                    const text = document.createElement('span');
                    text.textContent = item.quantity + 'x ' + item.name;

                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'js-package-remove';
                    remove.dataset.menuId = item.id;
                    remove.setAttribute('aria-label', 'Hapus ' + item.name + ' dari paket');
                    remove.textContent = 'x';

                    chip.appendChild(text);
                    chip.appendChild(remove);
                    list.appendChild(chip);
                });
            }

            function initPackageBuilders() {
                document.querySelectorAll('.js-package-builder').forEach((builder) => {
                    const toggle = builder.querySelector('.js-package-picker-toggle');
                    const panel = builder.querySelector('.js-package-picker-panel');
                    const search = builder.querySelector('.js-package-search');

                    toggle?.addEventListener('click', () => {
                        const isOpen = panel?.classList.toggle('is-open') || false;
                        toggle.textContent = isOpen ? 'Tutup Pilihan' : '+ Tambah Menu';
                    });

                    search?.addEventListener('input', () => {
                        const keyword = search.value.trim().toLowerCase();

                        builder.querySelectorAll('.package-picker-row').forEach((row) => {
                            const haystack = ((row.dataset.menuName || '') + ' ' + (row.dataset.menuCategory || '')).toLowerCase();
                            row.classList.toggle('is-hidden', keyword !== '' && !haystack.includes(keyword));
                        });
                    });

                    builder.querySelectorAll('.js-package-check').forEach((check) => {
                        check.addEventListener('change', () => {
                            const quantity = builder.querySelector('.js-package-qty[data-menu-id="' + check.dataset.menuId + '"]');

                            if (quantity) {
                                quantity.value = check.checked ? Math.max(1, Number(quantity.value || 0)) : 0;
                            }

                            syncPackageBuilder(builder);
                        });
                    });

                    builder.querySelectorAll('.js-package-qty').forEach((input) => {
                        input.addEventListener('input', () => {
                            input.value = Math.max(0, Number(input.value || 0));
                            syncPackageBuilder(builder);
                        });
                    });

                    builder.querySelector('.js-package-selected-list')?.addEventListener('click', (event) => {
                        const remove = event.target.closest('.js-package-remove');

                        if (!remove) {
                            return;
                        }

                        const quantity = builder.querySelector('.js-package-qty[data-menu-id="' + remove.dataset.menuId + '"]');

                        if (quantity) {
                            quantity.value = 0;
                        }

                        syncPackageBuilder(builder);
                    });

                    syncPackageBuilder(builder);
                });
            }

            function syncPackagePeriod(form) {
                if (!form) {
                    return;
                }

                const permanentInput = form.querySelector('.js-package-permanent');
                const period = form.querySelector('.js-package-period');

                if (!permanentInput || !period) {
                    return;
                }

                period.hidden = permanentInput.checked;
                period.querySelectorAll('input[type="date"]').forEach((input) => {
                    input.disabled = permanentInput.checked;

                    if (permanentInput.checked) {
                        input.value = '';
                    }
                });
            }

            function initPackagePeriods() {
                document.querySelectorAll('.package-form').forEach((form) => {
                    const permanentInput = form.querySelector('.js-package-permanent');

                    if (!permanentInput) {
                        return;
                    }

                    syncPackagePeriod(form);

                    permanentInput.addEventListener('change', () => {
                        syncPackagePeriod(form);
                    });
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
                const openMenuLink = document.querySelector('.js-open-table-menu');
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

                if (openMenuLink) {
                    openMenuLink.href = url;
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
    @include('partials.favicon')
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

            document.querySelectorAll('[data-create-menu-mode]').forEach((button) => {
                button.addEventListener('click', () => {
                    setCreateMenuInputMode(button.dataset.createMenuMode || 'manual', true);
                });
            });

            document.querySelectorAll('[data-stock-mode]').forEach((button) => {
                button.addEventListener('click', () => {
                    setStockInputMode(button.dataset.stockMode || 'manual');
                });
            });

            initStockBarcodeScanner();

            document.getElementById('createMenuBarcode')?.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter') {
                    return;
                }

                event.preventDefault();
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
            initPackageBuilders();
            initPackagePeriods();

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
                    prepareCreateMenuModal({ dataset: { modal: 'create-menu', category: @json(old('category', 'Makanan')) } }, true);
                    openModal('create-menu');
                @endif
            @endif

            @if ($section === 'stock' && $errors->any())
                openModal('stock-menu');
            @endif

            @if ($section === 'ingredients' && $errors->any())
                openModal(@json(old('modal_id', 'create-ingredient')));
            @endif

            @if ($section === 'tables' && $errors->any())
                openModal(@json(old('modal_id', 'create-table')));
            @endif

            // Barcode / QR Scanner
            let _qrScanner       = null;   // Html5Qrcode instance
            let _qrScannerTarget = null;   // <input> to fill
            let _qrCameras       = [];     // available cameras list
            let _qrActiveCamIdx  = 0;      // index into _qrCameras
            let _qrEscHandler    = null;
            let _qrScannerReady  = false;
            let _qrRawStream     = null;
            let _qrPreviewVideo  = null;
            let _qrScanRaf       = null;
            let _barcodeDetector = null;
            let _qrHasResult     = false;
            let _qrDecodeCanvas  = null;

            // Inject scanner modal + styles once
            function ensureQrModal() {
                if (document.getElementById('qrScannerModal')) return;

                // Inline styles for the scanner UI
                const style = document.createElement('style');
                style.textContent = [
                    '#qrScannerModal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;',
                    'background:rgba(0,0,0,.72);z-index:11000;backdrop-filter:blur(4px);}',
                    '#qrScannerBox{width:360px;max-width:94vw;background:#1a1a1a;border-radius:16px;',
                    'overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.6);}',
                    '#qrScannerHeader{display:flex;align-items:center;justify-content:space-between;',
                    'padding:14px 16px;background:#111;}',
                    '#qrScannerTitle{font-family:inherit;font-size:15px;font-weight:600;color:#fff;letter-spacing:.01em;}',
                    '#qrScannerCloseBtn{background:rgba(255,255,255,.08);border:none;color:#fff;width:30px;height:30px;',
                    'border-radius:50%;cursor:pointer;font-size:18px;line-height:1;display:flex;align-items:center;',
                    'justify-content:center;transition:background .15s;}',
                    '#qrScannerCloseBtn:hover{background:rgba(255,255,255,.18);}',
                    '#qrViewport{position:relative;width:100%;height:280px;background:#000;overflow:hidden;}',
                    '#qrReader{width:100%;height:100%;}',
                    '#qrReader video{width:100%!important;height:100%!important;object-fit:cover!important;}',
                    /* suppress html5-qrcode default UI chrome without hiding the camera/video */
                    '#qrReader img{display:none!important;}',
                    '#qrReader__dashboard,#qrReader__scan_region img{display:none!important;}',
                    '#qrScannerFooter{padding:12px 16px;background:#111;display:flex;align-items:center;',
                    'justify-content:space-between;gap:10px;}',
                    '#qrStatusMsg{font-size:13px;color:#9ca3af;flex:1;text-align:left;',
                    'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}',
                    '#qrStatusMsg.ok{color:#34d399;}',
                    '#qrStatusMsg.err{color:#f87171;}',
                    '#qrSwitchCamBtn{background:rgba(255,255,255,.08);border:none;color:#fff;',
                    'padding:6px 10px;border-radius:8px;cursor:pointer;font-size:12px;white-space:nowrap;',
                    'transition:background .15s;display:none;}',
                    '#qrSwitchCamBtn:hover{background:rgba(255,255,255,.18);}',
                    '#qrPreview{width:100%;height:100%;object-fit:cover;}',
                ].join('');
                document.head.appendChild(style);

                // Modal markup
                const wrap = document.createElement('div');
                wrap.id = 'qrScannerModal';
                wrap.innerHTML = [
                    '<div id="qrScannerBox">',
                    '  <div id="qrScannerHeader">',
                    '    <span id="qrScannerTitle">Scan Barcode / QR</span>',
                    '    <button type="button" id="qrScannerCloseBtn" aria-label="Tutup scanner">&times;</button>',
                    '  </div>',
                    '  <div id="qrViewport">',
                    '    <div id="qrReader"></div>',
                    '  </div>',
                    '  <div id="qrScannerFooter">',
                    '    <span id="qrStatusMsg">Menginisialisasi kamera...</span>',
                    '    <button type="button" id="qrSwitchCamBtn">&#x21C4; Ganti Kamera</button>',
                    '  </div>',
                    '</div>',
                ].join('');
                document.body.appendChild(wrap);

                document.getElementById('qrScannerCloseBtn')?.addEventListener('click', closeQrScanner);
                document.getElementById('qrSwitchCamBtn')?.addEventListener('click', switchCamera);
                wrap.addEventListener('click', (ev) => { if (ev.target === wrap) closeQrScanner(); });
            }

            function setQrStatus(msg, type) {
                const el = document.getElementById('qrStatusMsg');
                if (!el) return;
                el.textContent = msg;
                el.className = type || '';
            }

            function fillQrTarget(decodedText) {
    if (!decodedText || _qrHasResult) return;
    _qrHasResult = true;

    if (_qrScannerTarget) {
        _qrScannerTarget.value = decodedText;
        _qrScannerTarget.dispatchEvent(new Event('input', { bubbles: true }));
        _qrScannerTarget.dispatchEvent(new Event('change', { bubbles: true }));

        _qrScannerTarget.dispatchEvent(new KeyboardEvent('keydown', {
            key: 'Enter',
            code: 'Enter',
            keyCode: 13,
            which: 13,
            bubbles: true
        }));
    }

    setQrStatus('Terdeteksi: ' + decodedText, 'ok');
    setTimeout(async () => {
    await closeQrScanner();
    _qrHasResult = false;
}, 800);
}

            function getBarcodeFormats() {
                if (!window.Html5QrcodeSupportedFormats) {
                    return null;
                }

                const formats = window.Html5QrcodeSupportedFormats;
                return [
                    formats.QR_CODE,
                    formats.EAN_13,
                    formats.EAN_8,
                    formats.CODE_128,
                    formats.CODE_39,
                    formats.UPC_A,
                    formats.UPC_E,
                    formats.ITF,
                    formats.DATA_MATRIX,
                ].filter((format) => typeof format !== 'undefined');
            }

            function withTimeout(promise, ms) {
                let timeoutId = null;
                const timeout = new Promise((_, reject) => {
                    timeoutId = setTimeout(() => reject(new Error('timeout')), ms);
                });

                return Promise.race([promise, timeout]).finally(() => clearTimeout(timeoutId));
            }

            function checksumEan13(code) {
                if (!/^\d{13}$/.test(code)) return false;
                let sum = 0;
                for (let i = 0; i < 12; i++) {
                    sum += Number(code[i]) * (i % 2 === 0 ? 1 : 3);
                }
                return (10 - (sum % 10)) % 10 === Number(code[12]);
            }

            function bestDigit(bits, patterns) {
                let best = null;
                let bestDistance = 8;

                Object.entries(patterns).forEach(([digit, pattern]) => {
                    let distance = 0;
                    for (let i = 0; i < 7; i++) {
                        if (bits[i] !== pattern[i]) distance++;
                    }

                    if (distance < bestDistance) {
                        best = digit;
                        bestDistance = distance;
                    }
                });

                return bestDistance <= 1 ? { digit: best, distance: bestDistance } : null;
            }

            function bitDistance(a, b) {
                if (a.length !== b.length) return Number.POSITIVE_INFINITY;
                let distance = 0;
                for (let i = 0; i < a.length; i++) {
                    if (a[i] !== b[i]) distance++;
                }
                return distance;
            }

            function decodeEanBits(bits) {
                const leftOdd = {
                    0: '0001101', 1: '0011001', 2: '0010011', 3: '0111101', 4: '0100011',
                    5: '0110001', 6: '0101111', 7: '0111011', 8: '0110111', 9: '0001011',
                };
                const leftEven = {
                    0: '0100111', 1: '0110011', 2: '0011011', 3: '0100001', 4: '0011101',
                    5: '0111001', 6: '0000101', 7: '0010001', 8: '0001001', 9: '0010111',
                };
                const right = {
                    0: '1110010', 1: '1100110', 2: '1101100', 3: '1000010', 4: '1011100',
                    5: '1001110', 6: '1010000', 7: '1000100', 8: '1001000', 9: '1110100',
                };
                const parityMap = {
                    LLLLLL: '0', LLGLGG: '1', LLGGLG: '2', LLGGGL: '3', LGLLGG: '4',
                    LGGLLG: '5', LGGGLL: '6', LGLGLG: '7', LGLGGL: '8', LGGLGL: '9',
                };

                const guardDistance = bitDistance(bits.slice(0, 3), '101')
                    + bitDistance(bits.slice(45, 50), '01010')
                    + bitDistance(bits.slice(92, 95), '101');
                if (guardDistance > 2) {
                    return null;
                }

                let leftDigits = '';
                let parity = '';
                let distance = guardDistance;
                for (let i = 0; i < 6; i++) {
                    const chunk = bits.slice(3 + i * 7, 10 + i * 7);
                    const odd = bestDigit(chunk, leftOdd);
                    const even = bestDigit(chunk, leftEven);

                    if (!odd && !even) return null;
                    if (!even || (odd && odd.distance <= even.distance)) {
                        leftDigits += odd.digit;
                        parity += 'L';
                        distance += odd.distance;
                    } else {
                        leftDigits += even.digit;
                        parity += 'G';
                        distance += even.distance;
                    }
                }

                const firstDigit = parityMap[parity];
                if (typeof firstDigit === 'undefined') return null;

                let rightDigits = '';
                for (let i = 0; i < 6; i++) {
                    const chunk = bits.slice(50 + i * 7, 57 + i * 7);
                    const decoded = bestDigit(chunk, right);
                    if (!decoded) return null;
                    rightDigits += decoded.digit;
                    distance += decoded.distance;
                }

                const code = firstDigit + leftDigits + rightDigits;
                return checksumEan13(code) ? { code, distance } : null;
            }

            function otsuThreshold(values) {
                const histogram = new Array(256).fill(0);
                values.forEach((value) => { histogram[value]++; });

                let total = values.length;
                let sum = 0;
                for (let i = 0; i < 256; i++) sum += i * histogram[i];

                let sumB = 0;
                let weightB = 0;
                let maxVariance = 0;
                let threshold = 128;

                for (let i = 0; i < 256; i++) {
                    weightB += histogram[i];
                    if (!weightB) continue;

                    const weightF = total - weightB;
                    if (!weightF) break;

                    sumB += i * histogram[i];
                    const meanB = sumB / weightB;
                    const meanF = (sum - sumB) / weightF;
                    const variance = weightB * weightF * Math.pow(meanB - meanF, 2);

                    if (variance > maxVariance) {
                        maxVariance = variance;
                        threshold = i;
                    }
                }

                return threshold;
            }

            function scanEanFromVideo(video) {
                if (!video || video.readyState < 2) return null;

                const sourceWidth = video.videoWidth || 640;
                const sourceHeight = video.videoHeight || 480;
                const canvasWidth = 760;
                const canvasHeight = Math.max(260, Math.round(sourceHeight * (canvasWidth / sourceWidth)));

                if (!_qrDecodeCanvas) {
                    _qrDecodeCanvas = document.createElement('canvas');
                }

                _qrDecodeCanvas.width = canvasWidth;
                _qrDecodeCanvas.height = canvasHeight;
                const ctx = _qrDecodeCanvas.getContext('2d', { willReadFrequently: true });
                if (!ctx) return null;

                ctx.drawImage(video, 0, 0, canvasWidth, canvasHeight);

                const yStart = Math.round(canvasHeight * 0.28);
                const yEnd = Math.round(canvasHeight * 0.78);
                const rowStep = Math.max(4, Math.round(canvasHeight / 42));
                const moduleWidths = [1.8, 2, 2.25, 2.5, 2.75, 3, 3.25, 3.5, 3.8, 4.1, 4.45, 4.8, 5.2, 5.65, 6.1, 6.6, 7.1, 7.7];
                let best = null;

                for (let y = yStart; y <= yEnd; y += rowStep) {
                    const bandHeight = Math.min(5, canvasHeight - y);
                    const data = ctx.getImageData(0, y, canvasWidth, bandHeight).data;
                    const gray = new Array(canvasWidth);
                    for (let x = 0; x < canvasWidth; x++) {
                        let total = 0;
                        for (let bandY = 0; bandY < bandHeight; bandY++) {
                            const idx = (bandY * canvasWidth + x) * 4;
                            total += data[idx] * 0.299 + data[idx + 1] * 0.587 + data[idx + 2] * 0.114;
                        }
                        gray[x] = Math.round(total / bandHeight);
                    }

                    const threshold = otsuThreshold(gray);
                    for (const moduleWidth of moduleWidths) {
                        const barcodeWidth = moduleWidth * 95;
                        if (barcodeWidth >= canvasWidth) continue;

                        const startStep = Math.max(1, Math.round(moduleWidth));
                        for (let start = 0; start <= canvasWidth - barcodeWidth; start += startStep) {
                            let bits = '';
                            for (let i = 0; i < 95; i++) {
                                const sampleX = Math.min(canvasWidth - 1, Math.max(0, Math.round(start + (i + 0.5) * moduleWidth)));
                                bits += gray[sampleX] < threshold ? '1' : '0';
                            }

                            const decoded = decodeEanBits(bits);
                            if (decoded && (!best || decoded.distance < best.distance)) {
                                best = decoded;
                                if (decoded.distance === 0) return decoded.code;
                            }
                        }
                    }
                }

                return best?.code || null;
            }

            function ensureHtml5Qr() {
                return new Promise((resolve, reject) => {
                    if (typeof Html5Qrcode !== 'undefined') return resolve(window.Html5Qrcode);
                    const src = 'https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js';
                    const existing = document.querySelector('script[src="' + src + '"]');
                    if (existing) {
                        existing.addEventListener('load', () => resolve(window.Html5Qrcode));
                        existing.addEventListener('error', () => reject(new Error('Gagal memuat library scanner')));
                        return;
                    }
                    const s = document.createElement('script');
                    s.src = src;
                    s.async = true;
                    s.onload = () => resolve(window.Html5Qrcode);
                    s.onerror = () => reject(new Error('Gagal memuat library scanner'));
                    document.head.appendChild(s);
                });
            }

            async function startQrEngine(cameraId) {
                await stopQrEngine();

                if (!_qrScanner) {
                    _qrScanner = new Html5Qrcode('qrReader', { verbose: false });
                }

                const config = {
                    fps: 18,
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        const width = Math.floor(viewfinderWidth * 0.9);
                        const height = Math.floor(viewfinderHeight * 0.55);
                        return {
                            width: Math.max(240, Math.min(width, 420)),
                            height: Math.max(180, Math.min(height, 300)),
                        };
                    },
                    disableFlip: false,
                    aspectRatio: 1.333334,
                    experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                };
                const formatsToSupport = getBarcodeFormats();
                if (formatsToSupport?.length) {
                    config.formatsToSupport = formatsToSupport;
                }

                const startArg = cameraId
                    ? { deviceId: { exact: cameraId } }
                    : { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } };

                setQrStatus('Membuka kamera...');

                await _qrScanner.start(
                    startArg,
                    config,
                    (decodedText) => fillQrTarget(decodedText),
                    () => { /* scan miss - ignore */ }
                );

                _qrScannerReady = true;
                setQrStatus('Arahkan kamera ke barcode atau QR code');

                const switchBtn = document.getElementById('qrSwitchCamBtn');
                if (switchBtn) switchBtn.style.display = _qrCameras.length > 1 ? '' : 'none';
            }
            function stopRawPreview() {
                if (_qrScanRaf) {
                    cancelAnimationFrame(_qrScanRaf);
                    _qrScanRaf = null;
                }

                if (_qrPreviewVideo) {
                    try { _qrPreviewVideo.pause(); } catch (e) { /* ignore */ }
                    try { _qrPreviewVideo.srcObject = null; } catch (e) { /* ignore */ }
                    _qrPreviewVideo.remove();
                    _qrPreviewVideo = null;
                }

                if (_qrRawStream) {
                    try { _qrRawStream.getTracks().forEach((track) => track.stop()); } catch (e) { /* ignore */ }
                    _qrRawStream = null;
                }
            }

            async function showRawPreview(scanWithDetector = false) {
                stopRawPreview();

                const readerEl = document.getElementById('qrReader');
                if (!readerEl || !navigator.mediaDevices?.getUserMedia) {
                    throw new Error('Kamera tidak didukung browser ini.');
                }

                _qrRawStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false,
                });

                readerEl.innerHTML = '';
                const video = document.createElement('video');
                video.id = 'qrPreview';
                video.autoplay = true;
                video.playsInline = true;
                video.muted = true;
                video.srcObject = _qrRawStream;
                readerEl.appendChild(video);
                _qrPreviewVideo = video;

                try { await video.play(); } catch (e) { /* browser may autoplay after metadata */ }

                if (scanWithDetector) {
                    const loop = async () => {
                        if (!_qrPreviewVideo || _qrHasResult) return;

                        if (_qrPreviewVideo.readyState >= 2) {
                            if (_barcodeDetector) {
                                try {
                                    const detections = await _barcodeDetector.detect(_qrPreviewVideo);
                                    const first = detections?.[0];
                                    const decodedText = first?.rawValue || first?.rawData || '';
                                    if (decodedText) {
                                        fillQrTarget(decodedText);
                                        return;
                                    }
                                } catch (e) {
                                    // Some frames are unreadable while focus and exposure settle.
                                }
                            }

                            const localCode =
                                scanEanFromVideo(_qrPreviewVideo) ||
                                scanEanFromVideo(_qrPreviewVideo) ||
                                scanEanFromVideo(_qrPreviewVideo);

                            if (localCode) {
                                fillQrTarget(localCode);
                                return;
                            }
                        }

                        _qrScanRaf = requestAnimationFrame(loop);
                    };

                    _qrScanRaf = requestAnimationFrame(loop);
                }
            }

            async function startRawCameraScanner() {
                try {
                    if ('BarcodeDetector' in window && !_barcodeDetector) {
                        try {
                            const wantedFormats = ['qr_code', 'ean_13', 'ean_8', 'code_128', 'code_39', 'upc_a', 'upc_e', 'itf', 'data_matrix'];
                            const supportedFormats = BarcodeDetector.getSupportedFormats
                                ? await BarcodeDetector.getSupportedFormats()
                                : wantedFormats;
                            const formats = wantedFormats.filter((format) => supportedFormats.includes(format));
                            _barcodeDetector = new BarcodeDetector({ formats: formats.length ? formats : wantedFormats });
                        } catch (detectorErr) {
                            console.warn('BarcodeDetector unavailable, using local EAN scanner.', detectorErr);
                            _barcodeDetector = null;
                        }
                    }

                    setQrStatus('Meminta izin kamera...');
                    await showRawPreview(true);
                    _qrScannerReady = true;
                    const switchBtn = document.getElementById('qrSwitchCamBtn');
                    if (switchBtn) switchBtn.style.display = 'none';

                    setQrStatus('Tahan barcode 1 detik sampai terbaca...');
                    if (_barcodeDetector) {
                        return true;
                    }

                    await new Promise((resolve) => setTimeout(resolve, 1200));
                    return _qrHasResult;
                } catch (e) {
                    console.error('Raw camera start failed:', e);
                    stopRawPreview();
                    throw e;
                }
            }

            async function stopQrEngine() {
                _qrScannerReady = false;
                stopRawPreview();
                if (_qrScanner) {
                    try {
                        const state = _qrScanner.getState ? _qrScanner.getState() : 0;
                        // state 2 = SCANNING, state 3 = PAUSED
                        if (state === 2 || state === 3) {
                            await _qrScanner.stop();
                        }
                    } catch (e) { /* ignore */ }
                    try { _qrScanner.clear(); } catch (e) { /* ignore */ }
                    _qrScanner = null;
                }
            }
            _qrHasResult = false;
            async function openQrScanner(targetId) {
                ensureQrModal();

                const modal = document.getElementById('qrScannerModal');
                if (!modal) return;
                modal.style.display = 'flex';

                _qrScannerTarget = document.getElementById(targetId) || null;
                _qrHasResult = false;
                setQrStatus('Menginisialisasi kamera...');

                // Escape key to close
                if (!_qrEscHandler) {
                    _qrEscHandler = (ev) => { if (ev.key === 'Escape') closeQrScanner(); };
                    document.addEventListener('keydown', _qrEscHandler);
                }

                try {
                    if (await startRawCameraScanner()) {
                        return;
                    }
                } catch (cameraErr) {
                    if (cameraErr?.name === 'NotAllowedError' || cameraErr?.name === 'PermissionDeniedError') {
                        setQrStatus('Izin kamera diblokir. Klik ikon kamera di address bar lalu Allow.', 'err');
                        return;
                    }

                    if (cameraErr?.name === 'NotFoundError' || cameraErr?.name === 'DevicesNotFoundError') {
                        setQrStatus('Kamera tidak ditemukan di perangkat ini.', 'err');
                        return;
                    }

                    setQrStatus('Kamera belum bisa dibuka. Cek izin kamera browser.', 'err');
                    return;
                }

                try {
                    await ensureHtml5Qr();
                } catch (e) {
                    setQrStatus('Gagal memuat library scanner.', 'err');
                    return;
                }

                try {
                    await startQrEngine(null);
                } catch (err) {
                    console.error('Scanner start failed:', err);
                    try {
                        setQrStatus('Mencari kamera...');
                        _qrCameras = await withTimeout(Html5Qrcode.getCameras(), 1500);
                        _qrActiveCamIdx = 0;
                        if (_qrCameras.length > 1) {
                            const backIdx = _qrCameras.findIndex((c) =>
                                /back|rear|environment/i.test(c.label)
                            );
                            if (backIdx >= 0) _qrActiveCamIdx = backIdx;
                        }
                        const cameraId = _qrCameras.length ? _qrCameras[_qrActiveCamIdx].id : null;
                        if (cameraId) {
                            await startQrEngine(cameraId);
                        } else {
                            throw err;
                        }
                    } catch (cameraErr) {
                        console.error('Scanner camera-id fallback failed:', cameraErr);
                        await startQrEngine(null);
                    }
                }
            }

            async function closeQrScanner() {
                const modal = document.getElementById('qrScannerModal');
                if (modal) modal.style.display = 'none';

                await stopQrEngine();

                _qrScannerTarget = null;
                _qrCameras = [];

                if (_qrEscHandler) {
                    document.removeEventListener('keydown', _qrEscHandler);
                    _qrEscHandler = null;
                }
            }

            async function switchCamera() {
                if (_qrCameras.length < 2) return;
                _qrActiveCamIdx = (_qrActiveCamIdx + 1) % _qrCameras.length;
                const cameraId = _qrCameras[_qrActiveCamIdx].id;
                setQrStatus('Mengganti kamera...');
                try {
                    await startQrEngine(cameraId);
                } catch (e) {
                    setQrStatus('Gagal mengganti kamera.', 'err');
                }
            }

            // Delegate click on any .js-open-qr button
            document.addEventListener('click', (event) => {
                const btn = event.target.closest && event.target.closest('.js-open-qr');
                if (!btn) return;
                const target = btn.dataset.target;
                if (target) openQrScanner(target);
            });
        })();
    </script>
