<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Baker</title>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>
    @include('chef.partials.styles')
</head>
<body>
    <div class="chef-shell">
        @include('chef.partials.topbar')

        <main>
            <section class="hero-card">
                <div class="eyebrow">Baker SwiftBite</div>
                <h1 class="hero-title">Dashboard Baker</h1>
                <button type="button" id="enableBakerVoice" style="margin-top:12px;">
    Aktifkan Suara Pesanan
</button>
            </section>

            <section class="stats">
                <article class="stat-card"><span>Pesanan Diproses</span><strong>{{ $stats['pesanan_diproses'] }}</strong></article>
                <article class="stat-card"><span>Total Bahan</span><strong>{{ $stats['total_bahan'] }}</strong></article>
                <article class="stat-card"><span>Bahan Menipis</span><strong>{{ $stats['bahan_menipis'] }}</strong></article>
                <article class="stat-card"><span>Bahan Habis</span><strong>{{ $stats['bahan_habis'] }}</strong></article>
            </section>

            @php
                $attendanceNow = now();
                $checkInStartsAt = $attendanceNow->copy()->setTime(6, 0);
                $checkOutStartsAt = $attendanceNow->copy()->setTime(17, 0);
                $canCheckIn = !$todayAbsensi && $attendanceNow->greaterThanOrEqualTo($checkInStartsAt);
                $canCheckOut = $todayAbsensi && !$todayAbsensi->jam_keluar && $attendanceNow->greaterThanOrEqualTo($checkOutStartsAt);
            @endphp

            <section class="panel attendance-panel">
                <div class="attendance-head">
                    <div>
                        <h2>Absensi</h2>
                        <p class="muted">Status absensi hari ini & verifikasi hand gesture 2 jari.</p>
                    </div>
                </div>

                <table class="attendance-table">
                    <tbody>
                        <tr>
                            <td>
                                @if (!$todayAbsensi)
                                    <span class="attendance-status absent">Belum Absen</span>
                                    @unless ($canCheckIn)
                                        <span class="attendance-status-note">Absen masuk dibuka jam 06:00</span>
                                    @endunless
                                @elseif (!$todayAbsensi->jam_keluar)
                                    <span class="attendance-status in">Sudah Check In ({{ \Carbon\Carbon::parse($todayAbsensi->jam_masuk)->format('H:i') }})</span>
                                    @unless ($canCheckOut)
                                        <span class="attendance-status-note">Absen keluar dibuka jam 17:00</span>
                                    @endunless
                                @else
                                    <span class="attendance-status out">Sudah Check Out ({{ \Carbon\Carbon::parse($todayAbsensi->jam_keluar)->format('H:i') }})</span>
                                @endif
                            </td>
                            <td class="attendance-action">
                                @if (!$todayAbsensi)
                                    <button
                                        type="button"
                                        class="attendance-button {{ $canCheckIn ? 'check-in' : 'waiting' }}"
                                        @if ($canCheckIn) onclick="openAbsensiCamera('check-in')" @else disabled @endif
                                    >Absen Masuk</button>
                                @elseif (!$todayAbsensi->jam_keluar)
                                    <button
                                        type="button"
                                        class="attendance-button {{ $canCheckOut ? 'check-out' : 'waiting' }}"
                                        @if ($canCheckOut) onclick="openAbsensiCamera('check-out')" @else disabled @endif
                                    >Absen Keluar</button>
                                @else
                                    <span class="attendance-finished">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="color: #52c41a;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                        Absensi Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="grid">
                <div class="panel">
    <h2>Pesanan Untuk Dibuat</h2>

    <div id="bakerLiveOrders">
        @include('chef.partials.live-orders', ['processingOrders' => $processingOrders])
    </div>
</div>
                        
                </div>

                <div class="panel">
                    <h2>Stok Bahan</h2>
                    @if ($ingredients->isEmpty())
                        <p class="empty-state">Belum ada data bahan.</p>
                    @else
                        <div class="list-stack">
                            @foreach ($ingredients->take(6) as $ingredient)
                                <div class="ingredient-row">
                                    <div>{{ $ingredient->nama_bahan }}</div>
                                    <div class="small">{{ number_format($ingredient->stok, 2, ',', '.') }} {{ $ingredient->satuan }}</div>
                                    <div class="small"><span class="status {{ $ingredient->status_type }}">{{ $ingredient->status_label }}</span></div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        </main>
    </div>
<script>
    const attendanceFingerCount = 2;
    const attendanceGestureLabel = attendanceFingerCount + ' jari';
    let absensiStream = null;
    let activeCamera = null;

    function playSuccessBeep() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(523.25, audioCtx.currentTime);
            gain.gain.setValueAtTime(0, audioCtx.currentTime);
            gain.gain.linearRampToValueAtTime(0.15, audioCtx.currentTime + 0.05);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 0.3);
        } catch (e) {
            console.log('Web Audio API chime error:', e);
        }
    }

    function openAbsensiCamera(action) {
        if (!action) return;

        const existing = document.getElementById('absensi-camera-modal');
        if (existing) existing.remove();

        const overlay = document.createElement('div');
        overlay.id = 'absensi-camera-modal';
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(0,0,0,.6)';
        overlay.style.zIndex = '9999';
        overlay.style.display = 'grid';
        overlay.style.placeItems = 'center';
        overlay.innerHTML = `
            <div style="width:min(92vw,520px); background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.35);">
                <div style="padding:14px 16px; background:linear-gradient(135deg, #9a6239, #27140d); color:#fff6e8; display:flex; justify-content:space-between; align-items:center;">
                    <strong>Scan Absensi - ${action === 'check-in' ? 'Check In' : 'Check Out'}</strong>
                    <button type="button" id="absensi-modal-close" style="border:0; background:rgba(255,246,232,.18); color:#fff6e8; border-radius:8px; padding:8px 10px; font-weight:900; cursor:pointer;">Tutup</button>
                </div>
                <div style="padding:16px; display:grid; gap:12px;">
                    <div style="position:relative; width:100%; aspect-ratio: 4/3; border-radius:10px; overflow:hidden; background:#000;">
                        <video id="absensi-video" autoplay playsinline style="width:100%; height:100%; object-fit: cover; display:block;"></video>
                        <canvas id="absensi-canvas" style="position:absolute; inset:0; width:100%; height:100%; object-fit: cover; pointer-events:none;"></canvas>
                        <div id="loading-overlay" style="position:absolute; inset:0; background:rgba(0,0,0,0.7); display:flex; flex-direction:column; align-items:center; justify-content:center; color:#fff; z-index: 10;">
                            <div style="width:40px; height:40px; border:4px solid rgba(255,255,255,0.3); border-top-color:#9a6239; border-radius:50%; animation: spin 1s linear infinite; margin-bottom: 12px;"></div>
                            <span style="font-weight: 800;">Menginisialisasi Kamera & AI...</span>
                        </div>
                    </div>

                    <div style="padding: 12px; background: #fffbe6; border: 1px solid #ffe58f; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                        <div style="font-size: 24px;">Verifikasi</div>
                        <div style="flex: 1;">
                            <strong style="color: #d46b08; font-size: 13px; display: block;">Gesture ${attendanceGestureLabel} Diperlukan</strong>
                            <span style="color: #595959; font-size: 11px; display: block;">Tunjukkan ${attendanceGestureLabel} ke kamera hingga progress penuh.</span>
                        </div>
                    </div>

                    <div style="margin-top: 4px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 800; color: #2b1c15; margin-bottom: 6px;">
                            <span id="gesture-status">Mencari tangan...</span>
                            <span id="progress-percent">0%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #f0f0f0; border-radius: 4px; overflow: hidden;">
                            <div id="gesture-progress-bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #9a6239, #52c41a); transition: width 0.1s ease; border-radius: 4px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        const closeBtn = overlay.querySelector('#absensi-modal-close');
        closeBtn.addEventListener('click', () => closeAbsensiCamera());
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeAbsensiCamera();
        });

        const video = overlay.querySelector('#absensi-video');
        const canvasElement = overlay.querySelector('#absensi-canvas');
        const statusText = overlay.querySelector('#gesture-status');
        const percentText = overlay.querySelector('#progress-percent');
        const progressBar = overlay.querySelector('#gesture-progress-bar');

        let progress = 0;
        const progressTarget = 40;
        let absensiFinished = false;

        function captureAttendancePhoto() {
            const photoCanvas = document.createElement('canvas');
            photoCanvas.width = video.videoWidth || 640;
            photoCanvas.height = video.videoHeight || 480;
            const photoCtx = photoCanvas.getContext('2d');
            photoCtx.drawImage(video, 0, 0, photoCanvas.width, photoCanvas.height);
            if (canvasElement) {
                photoCtx.drawImage(canvasElement, 0, 0, photoCanvas.width, photoCanvas.height);
            }

            return photoCanvas.toDataURL('image/jpeg', 0.86);
        }

        async function triggerAbsensiSubmit() {
            if (absensiFinished) return;
            absensiFinished = true;

            statusText.textContent = 'Verifikasi Berhasil! Menyimpan...';
            statusText.style.color = '#52c41a';
            playSuccessBeep();

            try {
                const url = action === 'check-in'
                    ? '{{ route('attendance.checkIn') }}'
                    : '{{ route('attendance.checkOut') }}';

                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        scan_value: 'Hand Gesture ' + attendanceGestureLabel + ' Verified',
                        attendance_photo: captureAttendancePhoto()
                    })
                });

                if (!res.ok) {
                    const t = await res.text();
                    alert('Gagal absensi: ' + t);
                    closeAbsensiCamera();
                    return;
                }

                const modalContent = overlay.querySelector('div > div:nth-child(2)');
                if (modalContent) {
                    modalContent.innerHTML = `
                        <div style="padding: 40px 20px; text-align: center;">
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: #edf5e8; display: grid; place-items: center; margin: 0 auto 20px; color: #355b28;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            </div>
                            <h3 style="margin: 0 0 8px; color: #2b1c15; font-size: 20px; font-weight: 800;">Absensi Berhasil!</h3>
                            <p style="margin: 0; color: #8c8c8c; font-size: 13px;">Halaman akan disegarkan secara otomatis.</p>
                        </div>
                    `;
                }

                setTimeout(() => {
                    closeAbsensiCamera();
                    window.location.reload();
                }, 1500);
            } catch (e) {
                alert('Error: ' + (e?.message || e));
                closeAbsensiCamera();
            }
        }

        const hands = new Hands({
            locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
        });

        hands.setOptions({
            maxNumHands: 1,
            modelComplexity: 1,
            minDetectionConfidence: 0.6,
            minTrackingConfidence: 0.6
        });

        hands.onResults((results) => {
            const loadingOverlay = overlay.querySelector('#loading-overlay');
            if (loadingOverlay) loadingOverlay.style.display = 'none';

            if (!canvasElement || !video) return;
            const canvasCtx = canvasElement.getContext('2d');

            if (canvasElement.width !== video.videoWidth) {
                canvasElement.width = video.videoWidth;
                canvasElement.height = video.videoHeight;
            }

            canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);
            if (absensiFinished) return;

            let gestureDetected = false;

            if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                const landmarks = results.multiHandLandmarks[0];

                if (window.drawConnectors && window.HAND_CONNECTIONS) {
                    drawConnectors(canvasCtx, landmarks, HAND_CONNECTIONS, {color: '#9a6239', lineWidth: 4});
                    drawLandmarks(canvasCtx, landmarks, {color: '#ffffff', lineWidth: 1, radius: 4});
                    drawLandmarks(canvasCtx, landmarks, {color: '#27140d', lineWidth: 1, radius: 2});
                }

                const fingerStates = [
                    landmarks[8].y < landmarks[6].y,
                    landmarks[12].y < landmarks[10].y,
                    landmarks[16].y < landmarks[14].y,
                    landmarks[20].y < landmarks[18].y
                ];
                const expectedPattern = fingerStates.map((_, index) => index < attendanceFingerCount);
                gestureDetected = fingerStates.every((extended, index) => extended === expectedPattern[index]);

                if (gestureDetected && window.drawLandmarks) {
                    const highlightedTips = [8, 12, 16, 20]
                        .slice(0, attendanceFingerCount)
                        .map(index => landmarks[index]);
                    drawLandmarks(canvasCtx, highlightedTips, {color: '#52c41a', lineWidth: 2, radius: 6});
                }
            }

            if (gestureDetected) {
                progress++;
                const percentage = Math.min(Math.round((progress / progressTarget) * 100), 100);
                statusText.textContent = 'Tahan gesture ' + attendanceGestureLabel + '...';
                statusText.style.color = '#52c41a';
                percentText.textContent = percentage + '%';
                progressBar.style.width = percentage + '%';

                if (progress >= progressTarget) {
                    triggerAbsensiSubmit();
                }
            } else {
                progress = Math.max(0, progress - 1);
                const percentage = Math.min(Math.round((progress / progressTarget) * 100), 100);
                statusText.textContent = results.multiHandLandmarks && results.multiHandLandmarks.length > 0
                    ? 'Gunakan Gesture ' + attendanceGestureLabel
                    : 'Mencari tangan...';
                statusText.style.color = '#e76f51';
                percentText.textContent = percentage + '%';
                progressBar.style.width = percentage + '%';
            }
        });

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 }, audio: false })
            .then((stream) => {
                absensiStream = stream;
                video.srcObject = stream;
                video.play();

                activeCamera = new Camera(video, {
                    onFrame: async () => {
                        if (!absensiFinished) {
                            await hands.send({ image: video });
                        }
                    },
                    width: 640,
                    height: 480
                });
                activeCamera.start();
            })
            .catch((e) => {
                console.error('Camera access error:', e);
                const loadingOverlay = overlay.querySelector('#loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.innerHTML = `
                        <span style="color:#ff4d4f; font-weight:800; padding:20px; text-align:center;">
                            Kamera tidak tersedia atau izin ditolak. Izinkan akses kamera lalu coba ulangi absensi.
                        </span>
                    `;
                }
            });
    }

    function closeAbsensiCamera() {
        const modal = document.getElementById('absensi-camera-modal');
        if (activeCamera) {
            activeCamera.stop();
            activeCamera = null;
        }
        if (absensiStream) {
            absensiStream.getTracks().forEach(t => t.stop());
            absensiStream = null;
        }
        if (modal) modal.remove();
    }

    const bakerLiveOrdersUrl = @json(route('baker.orders.live'));
    const bakerLiveOrders = document.getElementById('bakerLiveOrders');
    const bakerProcessedStat = document.querySelector('.stat-card strong');
    const enableBakerVoice = document.getElementById('enableBakerVoice');

    let bakerVoiceEnabled = localStorage.getItem('baker_voice_enabled') === '1';
    let latestBakerOrderId = Math.max(
        0,
        ...Array.from(document.querySelectorAll('[data-order-id]'))
            .map(card => Number(card.dataset.orderId || 0))
    );

    function setVoiceButtonState() {
        if (!enableBakerVoice) return;

        if (bakerVoiceEnabled) {
            enableBakerVoice.textContent = 'Suara Pesanan Aktif';
        } else {
            enableBakerVoice.textContent = 'Aktifkan Suara Pesanan';
        }
    }

    function speakText(text) {
        if (!bakerVoiceEnabled) return;
        if (!('speechSynthesis' in window)) return;

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 1;
        utterance.pitch = 1;

        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    }

    function speakBakerOrder(order) {
        const tableName = order.table || 'Kasir Langsung';
        const itemNames = (order.items || []).slice(0, 3).join(', ');
        speakText('Pesanan baru dari ' + tableName + '. ' + itemNames + '.');
    }

    enableBakerVoice?.addEventListener('click', () => {
        bakerVoiceEnabled = true;
        localStorage.setItem('baker_voice_enabled', '1');
        setVoiceButtonState();

        const utterance = new SpeechSynthesisUtterance('Suara pesanan Baker aktif.');
        utterance.lang = 'id-ID';
        utterance.rate = 1;

        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    });

    async function refreshBakerOrders() {
        try {
            const response = await fetch(bakerLiveOrdersUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            console.log('LIVE BAKER:', data.latest_order_id, latestBakerOrderId, data.orders);

            if (Number(data.latest_order_id) > latestBakerOrderId) {
                const newOrder = (data.orders || []).find(order => Number(order.id) === Number(data.latest_order_id));
                if (newOrder) speakBakerOrder(newOrder);
                latestBakerOrderId = Number(data.latest_order_id);
            }

            if (bakerLiveOrders && data.html) {
                bakerLiveOrders.innerHTML = data.html;
            }

            if (bakerProcessedStat) {
                bakerProcessedStat.textContent = data.count;
            }
        } catch (error) {
            console.log('Gagal refresh pesanan baker', error);
        }
    }

    setVoiceButtonState();
    setInterval(refreshBakerOrders, 5000);
</script>
</body>
</html>
