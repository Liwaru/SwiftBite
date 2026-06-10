<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function checkIn(Request $request)
    {
        $payload = $request->input('scan_value');

        $user = $request->user() ?: \App\Models\User::find($request->session()->get('auth_user_id'));

        if (!$user) {
            return response()->json([
                'ok' => false,
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        $tanggal = now()->toDateString();

        $absensi = \App\Models\Absensi::updateOrCreate(
            [
                'id_user' => $user->id_user,
                'tanggal' => $tanggal,
            ],
            [
    'jam_masuk' => now()->format('H:i:s'),
    'status' => 'hadir',
]
        );

        return response()->json([
            'ok' => true,
            'message' => 'Check-in berhasil',
            'absensi_id' => $absensi->id_absensi,
            'scan' => $payload,
        ]);
    }

    public function checkOut(Request $request)
    {
        $payload = $request->input('scan_value');

        $user = $request->user() ?: \App\Models\User::find($request->session()->get('auth_user_id'));

        if (!$user) {
            return response()->json([
                'ok' => false,
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        $tanggal = now()->toDateString();

        $absensi = \App\Models\Absensi::updateOrCreate(
            [
                'id_user' => $user->id_user,
                'tanggal' => $tanggal,
            ],
            [
                'jam_keluar' => now()->format('H:i:s'),
            ]
        );


        return response()->json([
            'ok' => true,
            'message' => 'Check-out berhasil',
            'absensi_id' => $absensi->id_absensi,
            'scan' => $payload,
        ]);
    }
}