<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::with('user')
            ->latest()
            ->get();

        return view('manager.pages.absensi', compact('absensis'));
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