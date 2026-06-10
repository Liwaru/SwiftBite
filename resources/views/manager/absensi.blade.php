@extends('layouts.manager')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3>Data Absensi</h3>

        <a href="{{ route('absensi.create') }}" class="btn btn-primary">
            Tambah Absensi
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($absensis as $absensi)

                    <tr>

                        <td>
                            {{ $absensi->user->name ?? '-' }}
                        </td>

                        <td>
                            {{ $absensi->tanggal ?? '-' }}
                        </td>

                        <td>
                            {{ $absensi->jam_masuk ?? '-' }}
                        </td>

                        <td>
                            {{ $absensi->jam_keluar ?? '-' }}
                        </td>

                        <td>
                            {{ ucfirst($absensi->status ?? '-') }}
                        </td>

                        <td>

                            <a href="{{ route('absensi.edit', $absensi->id_absensi) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('absensi.destroy', $absensi->id_absensi) }}"
                                  method="POST"
                                  style="display:inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus data?')">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            Tidak ada data absensi
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection