<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $attendanceFilters = [
            'name' => trim((string) request('name', '')),
            'role' => (string) request('role', 'semua'),
            'date' => (string) request('date', ''),
            'status' => (string) request('status', 'semua'),
        ];

        $absensis = Absensi::with('user')
            ->when($attendanceFilters['name'] !== '', function ($query) use ($attendanceFilters) {
                $query->whereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $attendanceFilters['name'] . '%'));
            })
            ->when($attendanceFilters['role'] !== 'semua', function ($query) use ($attendanceFilters) {
                $query->whereHas('user', fn ($userQuery) => $userQuery->where('level', (int) $attendanceFilters['role']));
            })
            ->when($attendanceFilters['date'] !== '', fn ($query) => $query->whereDate('tanggal', $attendanceFilters['date']))
            ->when($attendanceFilters['status'] !== 'semua', function ($query) use ($attendanceFilters) {
                if ($attendanceFilters['status'] === 'hadir') {
                    $query->whereIn('status', ['hadir', 'masuk', 'keluar']);

                    return;
                }

                $query->where('status', $attendanceFilters['status']);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $page = [
            'title' => 'Data Absensi',
            'description' => 'Kelola data absensi karyawan dan catatan kehadiran.',
        ];
        $section = 'absensi';

        return view('manager.absensi', compact('absensis', 'page', 'section', 'attendanceFilters'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('manager.pages.absensi_create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'tanggal' => 'required',
            'status' => 'required'
        ]);

        Absensi::create($request->all());

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Data absensi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $absensi = Absensi::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('manager.pages.absensi_edit', compact('absensi', 'users'));
    }

    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $absensi->update($request->all());

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Data absensi berhasil diubah');
    }

    public function destroy($id)
    {
        Absensi::destroy($id);

        return redirect()
            ->route('absensi.index')
            ->with('success', 'Data absensi berhasil dihapus');
    }
}
