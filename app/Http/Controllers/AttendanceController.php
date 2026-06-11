<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    private function requiredFingerCountFor($user): int
    {
        return match ((int) $user->level) {
            1 => 1,
            2 => 2,
            3 => 3,
            default => 2,
        };
    }

    private function validateGesturePayload(Request $request, $user)
    {
        $requiredFingerCount = $this->requiredFingerCountFor($user);
        $payload = (string) $request->input('scan_value', '');

        if (!str_contains($payload, 'Hand Gesture ' . $requiredFingerCount . ' jari Verified')) {
            return response()->json([
                'ok' => false,
                'message' => 'Gesture absensi untuk role ini harus ' . $requiredFingerCount . ' jari'
            ], 422);
        }

        return null;
    }

    private function storeAttendancePhoto(Request $request, $user, string $type): string
    {
        $photo = (string) $request->input('attendance_photo', '');

        if (! preg_match('/^data:image\/(png|jpe?g|webp);base64,/', $photo, $matches)) {
            abort(response()->json([
                'ok' => false,
                'message' => 'Foto absensi wajib dikirim dari kamera'
            ], 422));
        }

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $base64 = substr($photo, strpos($photo, ',') + 1);
        $binary = base64_decode($base64, true);

        if ($binary === false || strlen($binary) < 1024) {
            abort(response()->json([
                'ok' => false,
                'message' => 'Foto absensi tidak valid'
            ], 422));
        }

        $directory = 'attendance/' . now()->format('Y-m');
        $fileName = $type . '-' . $user->id_user . '-' . now()->format('YmdHis') . '-' . Str::random(8) . '.' . $extension;
        $path = $directory . '/' . $fileName;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }

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

        if ($gestureError = $this->validateGesturePayload($request, $user)) {
            return $gestureError;
        }

        $now = now();
        $tanggal = $now->toDateString();
        $checkInStartsAt = $now->copy()->setTime(6, 0);

        if ($now->lessThan($checkInStartsAt)) {
            return response()->json([
                'ok' => false,
                'message' => 'Absen masuk baru dibuka jam 06:00'
            ], 422);
        }

        $existingAbsensi = \App\Models\Absensi::where('id_user', $user->id_user)
            ->where('tanggal', $tanggal)
            ->first();

        if ($existingAbsensi?->jam_masuk) {
            return response()->json([
                'ok' => false,
                'message' => 'Anda sudah absen masuk hari ini'
            ], 422);
        }

        $photoPath = $this->storeAttendancePhoto($request, $user, 'masuk');

        $absensi = \App\Models\Absensi::updateOrCreate(
            [
                'id_user' => $user->id_user,
                'tanggal' => $tanggal,
            ],
            [
                'jam_masuk' => $now->format('H:i:s'),
                'foto_masuk' => $photoPath,
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

        if ($gestureError = $this->validateGesturePayload($request, $user)) {
            return $gestureError;
        }

        $now = now();
        $tanggal = $now->toDateString();
        $checkOutStartsAt = $now->copy()->setTime(17, 0);

        if ($now->lessThan($checkOutStartsAt)) {
            return response()->json([
                'ok' => false,
                'message' => 'Absen keluar baru dibuka jam 17:00'
            ], 422);
        }

        $existingAbsensi = \App\Models\Absensi::where('id_user', $user->id_user)
            ->where('tanggal', $tanggal)
            ->first();

        if (!$existingAbsensi?->jam_masuk) {
            return response()->json([
                'ok' => false,
                'message' => 'Anda harus absen masuk terlebih dahulu'
            ], 422);
        }

        if ($existingAbsensi->jam_keluar) {
            return response()->json([
                'ok' => false,
                'message' => 'Anda sudah absen keluar hari ini'
            ], 422);
        }

        $photoPath = $this->storeAttendancePhoto($request, $user, 'pulang');

        $absensi = \App\Models\Absensi::updateOrCreate(
            [
                'id_user' => $user->id_user,
                'tanggal' => $tanggal,
            ],
            [
                'jam_keluar' => $now->format('H:i:s'),
                'foto_pulang' => $photoPath,
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
