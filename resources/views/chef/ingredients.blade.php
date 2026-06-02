<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Bahan Chef</title>
    @include('chef.partials.styles')
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                <section class="hero-card">
                    <div class="eyebrow">Dapur SwiftBite</div>
                    <h1 class="hero-title">Data Bahan</h1>
                    <p class="hero-subtitle">Lihat stok bahan dan catat penggunaan bahan saat proses produksi.</p>
                </section>

                <section class="panel">
                    <h2>Stok Bahan</h2>

                    @if (session('success') || $errors->any())
                        @if (session('success'))
                            <div class="success-banner">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="error-banner">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if ($ingredients->isEmpty())
                        <p class="empty-state">Belum ada bahan baku yang terdaftar.</p>
                    @else
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama Bahan</th>
                                        <th>Stok</th>
                                        <th>Dipakai Hari Ini</th>
                                        <th>Status</th>
                                        <th>Gunakan Bahan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ingredients as $ingredient)
                                        <tr>
                                            <td>{{ $ingredient->nama_bahan }}</td>
                                            <td>{{ number_format($ingredient->stok, 2, ',', '.') }} {{ $ingredient->satuan }}</td>
                                            <td>{{ number_format($ingredient->used_today ?? 0, 2, ',', '.') }} {{ $ingredient->satuan }}</td>
                                            <td><span class="status {{ $ingredient->status_type }}">{{ $ingredient->status_label }}</span></td>
                                            <td>
                                                <form method="POST" action="{{ route('chef.ingredients.use', $ingredient) }}" class="action-form">
                                                    @csrf
                                                    <input type="number" name="qty" min="0.01" max="{{ $ingredient->stok }}" step="0.01" placeholder="Qty" required>
                                                    <input type="text" name="note" maxlength="120" placeholder="Catatan">
                                                    <button type="submit">Pakai</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </main>
        </div>
    </div>
</body>
</html>
