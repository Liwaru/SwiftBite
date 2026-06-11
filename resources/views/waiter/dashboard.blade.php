<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Antar</title>
    
    <!-- MediaPipe Hands & Drawing dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js" crossorigin="anonymous"></script>

    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        :root {
            --brown: #5a321f;
            --brown-dark: #27140d;
            --brown-light: #9a6239;
            --cream: #fff6e8;
            --cream-soft: #fffaf2;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        html, body, * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { display: none; width: 0; height: 0; }
        body { margin: 0; min-height: 100vh; background: #ffffff; color: #2b1c15; }
        .waiter-shell { min-height: 100vh; background: #ffffff; }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            padding: 14px 16px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown) 48%, var(--brown-dark));
            color: var(--cream);
            box-shadow: 0 8px 26px rgba(39, 20, 13, .18);
        }
        .brand { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .logo { width: 48px; height: 48px; display: grid; place-content: center; border-radius: 8px; background: var(--cream); overflow: hidden; }
        .logo img { width: 100%; height: 100%; display: block; border-radius: inherit; object-fit: cover; }
        .brand strong, .brand span { display: block; white-space: nowrap; }
        .brand span { color: rgba(255, 246, 232, .75); font-size: 12px; font-weight: 800; }
        .account { display: flex; align-items: center; gap: 8px; }
        .account-name { max-width: 96px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 13px; font-weight: 900; }
        .logout { margin: 0; }
        .logout button { border: 1px solid rgba(255, 246, 232, .32); border-radius: 7px; background: rgba(255, 246, 232, .12); color: var(--cream); padding: 8px 10px; font: inherit; font-size: 13px; font-weight: 900; cursor: pointer; }
        main { width: min(100%, 760px); margin: 0 auto; padding: 24px 16px 44px; }
        h1, h2, h3, p { margin: 0; }
        h1 { font-size: clamp(28px, 8vw, 40px); margin-bottom: 16px; }
        h2 { font-size: 20px; margin-bottom: 14px; }
        h3 { font-size: 18px; margin-bottom: 6px; }
        .hero-card {
            margin-bottom: 16px;
            padding: 18px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: var(--cream);
            box-shadow: 0 12px 30px rgba(39, 20, 13, .18);
        }
        .eyebrow { margin-bottom: 7px; color: rgba(255, 246, 232, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        .hero-title { margin: 0; font-size: clamp(30px, 8vw, 42px); line-height: 1.05; }
        .hero-subtitle { margin-top: 9px; color: rgba(255, 246, 232, .82); line-height: 1.5; }
        .notice { padding: 12px 14px; border-radius: 8px; margin-bottom: 16px; background: #edf5e8; color: #355b28; border: 1px solid #c5ddb7; font-weight: 800; }
        .panel {
            background: linear-gradient(135deg, rgba(154, 98, 57, .94), rgba(90, 50, 31, .98) 52%, rgba(39, 20, 13, .98));
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(39, 20, 13, .18);
            color: var(--cream);
            padding: 16px;
        }
        .tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px; }
        .summary-table { width: 100%; margin-bottom: 16px; border-collapse: collapse; border-radius: 8px; overflow: hidden; }
        .summary-table th, .summary-table td { padding: 11px 12px; border-top: 1px solid rgba(255, 246, 232, .16); text-align: left; }
        .summary-table th { background: rgba(255, 246, 232, .1); color: rgba(255, 246, 232, .78); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .summary-table th:last-child,
        .summary-table td:last-child { text-align: center; font-weight: 900; }
        .attendance-head { display: flex; justify-content: space-between; gap: 12px; align-items: flex-end; margin-bottom: 12px; }
        .attendance-table { margin-bottom: 0; table-layout: fixed; }
        .attendance-table td { border-top: 0; }
        .attendance-table th,
        .attendance-table td { width: 50%; vertical-align: middle; }
        .attendance-table th:last-child,
        .attendance-table td:last-child { text-align: right; }
        .attendance-status { justify-content: center; max-width: 100%; text-align: center; white-space: normal; }
        .attendance-status-note { display: block; margin-top: 7px; color: rgba(255, 246, 232, .68); font-size: 12px; font-weight: 800; }
        .attendance-action { text-align: right; }
        .attendance-button {
            width: 100%;
            max-width: 180px;
            border: 0;
            border-radius: 6px;
            padding: 8px 12px;
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
        }
        .attendance-button:disabled { cursor: not-allowed; opacity: .58; }
        .attendance-button.check-in { background: var(--cream); color: var(--brown-dark); }
        .attendance-button.check-out { background: #fa8c16; color: #fff; }
        .attendance-button.waiting { background: rgba(255, 246, 232, .2); color: rgba(255, 246, 232, .82); border: 1px solid rgba(255, 246, 232, .26); }
        .attendance-finished { color: #52c41a; font-weight: 900; font-size: 13px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; }
        .tab { border: 1px solid rgba(255, 246, 232, .24); border-radius: 7px; background: rgba(255, 246, 232, .1); color: var(--cream); padding: 10px 12px; font-weight: 900; text-align: center; text-decoration: none; }
        .tab.active { background: var(--cream); border-color: var(--cream); color: var(--brown-dark); }
        .order-list { display: grid; gap: 12px; }
        .order-card { display: grid; gap: 15px; border: 1px solid rgba(255, 246, 232, .22); border-radius: 8px; background: rgba(255, 246, 232, .08); padding: 15px; }
        .card-head { display: flex; justify-content: space-between; gap: 12px; align-items: flex-start; }
        .badge-row { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 12px; }
        .badge { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: rgba(255, 246, 232, .16); color: var(--cream); font-size: 12px; font-weight: 900; }
        .badge.payment { background: #edf5e8; color: #355b28; }
        .price { font-size: 17px; font-weight: 900; white-space: nowrap; }
        .muted { color: rgba(255, 246, 232, .76); line-height: 1.5; }
        .items { display: grid; gap: 7px; padding-top: 2px; }
        .flow-track { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 5px; }
        .flow-step { min-width: 0; border: 1px solid rgba(255, 246, 232, .18); border-radius: 7px; padding: 8px 4px; background: rgba(255, 246, 232, .08); color: rgba(255, 246, 232, .62); text-align: center; font-size: 11px; font-weight: 900; }
        .flow-step.done { background: rgba(237, 245, 232, .16); color: #dffbd8; border-color: rgba(197, 221, 183, .42); }
        .flow-step.current { background: var(--cream); color: var(--brown-dark); border-color: var(--cream); }
        .note { display: grid; gap: 3px; border-top: 1px solid rgba(255, 246, 232, .16); padding-top: 12px; }
        .note span { color: rgba(255, 246, 232, .68); font-size: 12px; font-weight: 800; }
        .action button { width: 100%; border: 0; border-radius: 7px; background: var(--cream); color: var(--brown-dark); padding: 12px 13px; font: inherit; font-weight: 900; cursor: pointer; }
        .status-done { display: inline-flex; justify-content: center; width: 100%; border-radius: 7px; background: #edf5e8; color: #355b28; padding: 11px 13px; font-weight: 900; }
        .empty { padding: 10px 0 2px; }
        .pagination { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; margin-top: 14px; }
        .pagination-info { color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .pagination-links { display: flex; flex-wrap: wrap; gap: 7px; }
        .page-link, .page-current, .page-disabled { min-width: 34px; box-sizing: border-box; border-radius: 7px; padding: 8px 10px; text-align: center; font-size: 13px; font-weight: 900; }
        .page-link { border: 1px solid rgba(255, 246, 232, .26); color: var(--cream); text-decoration: none; background: rgba(255, 246, 232, .1); }
        .page-current { background: var(--cream); color: var(--brown-dark); }
        .page-disabled { border: 1px solid rgba(255, 246, 232, .12); color: rgba(255, 246, 232, .45); }
        @media (max-width: 480px) {
            .topbar { padding: 12px; }
            main { padding: 20px 12px 36px; }
            .account-name { display: none; }
            .card-head { flex-direction: column; }
            .attendance-head { align-items: flex-start; }
            .attendance-table th,
            .attendance-table td { padding: 10px 12px; }
            .attendance-table th:last-child,
            .attendance-table td:last-child { text-align: right; }
            .attendance-status { justify-content: flex-start; width: fit-content; }
            .attendance-button { max-width: 132px; padding-inline: 10px; }
            .attendance-finished { justify-content: flex-start; }
        }
    </style>
</head>
<body>
    <div class="waiter-shell">
        <header class="topbar">
            <div class="brand">
                <span class="logo">
                    <img src="{{ asset('images/Swiftbite.png') }}" alt="SwiftBite">
                </span>
                <div>
                    <strong>SwiftBite</strong>
                    <span>Waiter</span>
                </div>
            </div>
            <div class="account">
                <form class="logout" method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </header>

        <main>
            <!-- 1. Hero Card: Pesanan Antar -->
            <section class="hero-card">
                <h1 class="hero-title">Pesanan Antar</h1>
                <p class="hero-subtitle">Lihat pesanan yang siap diantar dari baker dan tandai setelah sampai ke customer.</p>
            </section>

            @if (session('success'))
                <div class="notice">{{ session('success') }}</div>
            @endif

            @php
                $attendanceNow = now();
                $checkInStartsAt = $attendanceNow->copy()->setTime(6, 0);
                $checkOutStartsAt = $attendanceNow->copy()->setTime(17, 0);
                $canCheckIn = !$todayAbsensi && $attendanceNow->greaterThanOrEqualTo($checkInStartsAt);
                $canCheckOut = $todayAbsensi && !$todayAbsensi->jam_keluar && $attendanceNow->greaterThanOrEqualTo($checkOutStartsAt);
            @endphp

            <!-- 2. Table Absensi (Moved to the Top) -->
            <section class="panel" style="margin-bottom: 20px;">
                <div class="attendance-head">
                    <div>
                        <h2 style="margin:0; font-size:20px; margin-bottom:6px;">Absensi</h2>
                        <p class="muted" style="margin:0; color: rgba(255, 246, 232, .76); font-size: 13px;">Status absensi hari ini & verifikasi hand gesture 1 jari.</p>
                    </div>
                </div>

                <table class="summary-table attendance-table">
                    <tbody>
                        <tr>
                            <td data-label="Status" style="font-weight:900;">
                                @if (!$todayAbsensi)
                                    <span class="badge attendance-status" style="background:#ff4d4f; color:#fff; border-radius: 6px; padding: 4px 10px; font-size: 13px;">Belum Absen</span>
                                    @unless ($canCheckIn)
                                        <span class="attendance-status-note">Absen masuk dibuka jam 06:00</span>
                                    @endunless
                                @elseif (!$todayAbsensi->jam_keluar)
                                    <span class="badge attendance-status" style="background:#2f54eb; color:#fff; border-radius: 6px; padding: 4px 10px; font-size: 13px;">Sudah Check In ({{ \Carbon\Carbon::parse($todayAbsensi->jam_masuk)->format('H:i') }})</span>
                                    @unless ($canCheckOut)
                                        <span class="attendance-status-note">Absen keluar dibuka jam 17:00</span>
                                    @endunless
                                @else
                                    <span class="badge attendance-status" style="background:#52c41a; color:#fff; border-radius: 6px; padding: 4px 10px; font-size: 13px;">Sudah Check Out ({{ \Carbon\Carbon::parse($todayAbsensi->jam_keluar)->format('H:i') }})</span>
                                @endif
                            </td>
                            <td class="attendance-action" data-label="Aksi">
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

            <!-- 3. Table Siap Antar & List Menu yg Diantar (Moved Below Absensi) -->
            <section class="panel">
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Siap Diantar</td><td>{{ $stats['aktif'] }}</td></tr>
                        <tr><td>Selesai Hari Ini</td><td>{{ $stats['selesai_today'] }}</td></tr>
                    </tbody>
                </table>

                <div class="tabs">
                    <a class="tab {{ $status === 'aktif' ? 'active' : '' }}" href="{{ route('waiter.dashboard', ['per_page' => $perPage]) }}">Aktif</a>
                    <a class="tab {{ $status === 'selesai' ? 'active' : '' }}" href="{{ route('waiter.dashboard', ['status' => 'selesai', 'per_page' => $perPage]) }}">Selesai</a>
                </div>

                <div class="order-list">
                    @forelse ($orders as $order)
                        @php
                            $paymentLabel = strtoupper($order->payment_label);
                            $actionLabel = 'Sudah Diantar';
                            $waiterStatusLabel = in_array($order->status, ['menunggu_pembayaran', 'selesai'], true)
                                ? 'Sudah Diantar'
                                : $order->status_label;
                            $flowStep = match ($order->status) {
                                'diproses' => 2,
                                'siap_diantar' => 3,
                                'menunggu_pembayaran', 'selesai' => 4,
                                default => 1,
                            };
                            $flowSteps = [1 => 'Cashier', 2 => 'Baker', 3 => 'Waiter', 4 => 'Selesai'];
                            $waitingMinutes = $order->created_at ? (int) $order->created_at->diffInMinutes(now()) : 0;
                            $waitingText = $waitingMinutes < 60
                                ? max(1, $waitingMinutes) . ' menit lalu'
                                : floor($waitingMinutes / 60) . ' jam ' . ($waitingMinutes % 60) . ' menit lalu';
                        @endphp
                        <article class="order-card">
                            <div class="card-head">
                                <div>
                                    <div class="badge-row">
                                        <span class="badge">{{ $waiterStatusLabel }}</span>
                                        <span class="badge payment">{{ $paymentLabel }}</span>
                                    </div>
                                    <h3>{{ $order->diningTable?->name ?? 'Meja' }}</h3>
                                    <p class="muted">{{ $waitingText }}</p>
                                </div>
                                <span class="price">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>

                            <div class="items">
                                @foreach ($order->items as $item)
                                    <p>{{ $item->quantity }}x {{ $item->menu_name }} <span class="muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span></p>
                                @endforeach
                            </div>

                            <div class="flow-track" aria-label="Alur pesanan">
                                @foreach ($flowSteps as $step => $label)
                                    <span class="flow-step {{ $flowStep > $step ? 'done' : '' }} {{ $flowStep === $step ? 'current' : '' }}">{{ $label }}</span>
                                @endforeach
                            </div>

                            <div class="note">
                                <span>Catatan pelanggan</span>
                                <p>{{ $order->notes ?: '-' }}</p>
                            </div>

                            @if ($order->status === 'siap_diantar')
                                <form class="action" method="post" action="{{ route('waiter.orders.complete', $order) }}">
                                    @csrf
                                    @method('patch')
                                    <button type="submit">{{ $actionLabel }}</button>
                                </form>
                            @else
                                <span class="status-done">Tugas Waiter Selesai</span>
                            @endif
                        </article>
                    @empty
                        <p class="muted empty">
                            {{ $status === 'aktif' ? 'Belum ada pesanan yang siap diantar.' : 'Belum ada pesanan selesai.' }}
                        </p>
                    @endforelse
                </div>

                @if ($orders->hasPages())
                    <nav class="pagination" aria-label="Pagination pesanan waiter">
                        <span class="pagination-info">
                            Menampilkan {{ $orders->firstItem() }}-{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                        </span>
                        <div class="pagination-links">
                            @if ($orders->onFirstPage())
                                <span class="page-disabled">Prev</span>
                            @else
                                <a class="page-link" href="{{ $orders->previousPageUrl() }}">Prev</a>
                            @endif

                            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                @if ($page === $orders->currentPage())
                                    <span class="page-current">{{ $page }}</span>
                                @else
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($orders->hasMorePages())
                                <a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a>
                            @else
                                <span class="page-disabled">Next</span>
                            @endif
                        </div>
                    </nav>
                @endif
            </section>
        </main>
    </div>
    <script>
        (function () {
            const targetPerPage = window.innerWidth <= 760 ? '3' : '5';
            const url = new URL(window.location.href);

            if (url.searchParams.get('per_page') !== targetPerPage) {
                url.searchParams.set('per_page', targetPerPage);
                url.searchParams.delete('page');
                window.location.replace(url.toString());
            }
        })();

        const attendanceFingerCount = 1;
        const attendanceGestureLabel = attendanceFingerCount + ' jari';
        let absensiStream = null;
        let activeCamera = null;

        function playSuccessBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                
                // Beep 1
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(523.25, audioCtx.currentTime); // C5
                gain.gain.setValueAtTime(0, audioCtx.currentTime);
                gain.gain.linearRampToValueAtTime(0.15, audioCtx.currentTime + 0.05);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                
                osc.start(audioCtx.currentTime);
                osc.stop(audioCtx.currentTime + 0.3);

                // Beep 2 (slightly later and higher pitch)
                setTimeout(() => {
                    const osc2 = audioCtx.createOscillator();
                    const gain2 = audioCtx.createGain();
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(659.25, audioCtx.currentTime); // E5
                    gain2.gain.setValueAtTime(0, audioCtx.currentTime);
                    gain2.gain.linearRampToValueAtTime(0.15, audioCtx.currentTime + 0.05);
                    gain2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
                    osc2.start(audioCtx.currentTime);
                    osc2.stop(audioCtx.currentTime + 0.3);
                }, 100);

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
                            
                            <!-- Loading overlay -->
                            <div id="loading-overlay" style="position:absolute; inset:0; background:rgba(0,0,0,0.7); display:flex; flex-direction:column; align-items:center; justify-content:center; color:#fff; z-index: 10;">
                                <div style="width:40px; height:40px; border:4px solid rgba(255,255,255,0.3); border-top-color:#9a6239; border-radius:50%; animation: spin 1s linear infinite; margin-bottom: 12px;"></div>
                                <span style="font-weight: 800;">Menginisialisasi Kamera & AI...</span>
                            </div>
                        </div>

                        <div style="padding: 12px; background: #fffbe6; border: 1px solid #ffe58f; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                            <div style="font-size: 24px;">✌️</div>
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
            const progressTarget = 40; // 1.3 seconds at 30 fps
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

                if (statusText) {
                    statusText.textContent = 'Verifikasi Berhasil! Menyimpan...';
                    statusText.style.color = '#52c41a';
                }

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

            // Setup MediaPipe Hands
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

                // Match dimensions
                if (canvasElement.width !== video.videoWidth) {
                    canvasElement.width = video.videoWidth;
                    canvasElement.height = video.videoHeight;
                }

                canvasCtx.clearRect(0, 0, canvasElement.width, canvasElement.height);

                if (absensiFinished) return;

                let gestureDetected = false;

                if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                    const landmarks = results.multiHandLandmarks[0];

                    // Draw connectors & landmarks
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

                    if (gestureDetected) {
                        if (window.drawLandmarks) {
                            const highlightedTips = [8, 12, 16, 20]
                                .slice(0, attendanceFingerCount)
                                .map(index => landmarks[index]);
                            drawLandmarks(canvasCtx, highlightedTips, {color: '#52c41a', lineWidth: 2, radius: 6});
                        }
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

            // Start Camera
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
    </script>
</body>
</html>
