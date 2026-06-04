<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meja Tidak Aktif | SwiftBite</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background:
                linear-gradient(135deg, rgba(53, 32, 22, .86), rgba(111, 69, 43, .92)),
                #6f452b;
            color: #2b1c15;
            padding: 18px;
            box-sizing: border-box;
        }
        .card {
            width: min(420px, 100%);
            border-radius: 8px;
            background: #fff6e8;
            border: 1px solid #e1ad73;
            box-shadow: 0 18px 42px rgba(39, 20, 13, .22);
            padding: 22px;
            box-sizing: border-box;
        }
        .brand { color: #7a5a46; font-size: 13px; font-weight: 900; text-transform: uppercase; }
        h1 { margin: 10px 0 8px; font-size: 30px; line-height: 1.1; }
        p { margin: 0; color: #7a5a46; line-height: 1.55; }
        .table { margin-top: 18px; border-radius: 8px; background: #fffdfa; border: 1px solid #ead4ba; padding: 13px; font-weight: 900; }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">SwiftBite Morning Bakery</div>
        <h1>Meja Nonaktif</h1>
        <p>QR meja ini sedang tidak bisa digunakan untuk membuat pesanan. Silakan hubungi kasir atau pindai QR meja lain yang aktif.</p>
        <div class="table">{{ $table->nama_meja }}</div>
    </main>
</body>
</html>
