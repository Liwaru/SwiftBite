<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaiterDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default database state
        $this->artisan('db:seed');
    }

    public function test_waiter_dashboard_redirects_unauthenticated_user()
    {
        $response = $this->get(route('waiter.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_waiter_dashboard_accessible_with_session()
    {
        $waiter = User::where('name', 'waiter')->first();

        // Set actingAs to bypass auth middleware, and session for custom simple.auth check
        $response = $this->actingAs($waiter)
            ->withSession([
                'auth_user_id' => $waiter->id_user,
                'auth_user' => $waiter->name,
                'auth_name' => $waiter->name,
                'auth_level' => $waiter->level,
            ])
            ->get(route('waiter.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText($waiter->name);
        $response->assertSeeText('Absensi');
        $response->assertSeeText('Belum Absen');
    }

    public function test_waiter_dashboard_shows_check_in_status()
    {
        $waiter = User::where('name', 'waiter')->first();
        
        $tanggal = now()->toDateString();

        Absensi::create([
            'id_user' => $waiter->id_user,
            'tanggal' => $tanggal,
            'jam_masuk' => '08:00:00',
            'status' => 'masuk',
        ]);

        $response = $this->actingAs($waiter)
            ->withSession([
                'auth_user_id' => $waiter->id_user,
                'auth_user' => $waiter->name,
                'auth_name' => $waiter->name,
                'auth_level' => $waiter->level,
            ])
            ->get(route('waiter.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText('Sudah Check In (08:00)');
        $response->assertSeeText('Absen Keluar');
    }

    public function test_waiter_dashboard_shows_check_out_status()
    {
        $waiter = User::where('name', 'waiter')->first();
        $tanggal = now()->toDateString();

        Absensi::create([
            'id_user' => $waiter->id_user,
            'tanggal' => $tanggal,
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00',
            'status' => 'keluar',
        ]);

        $response = $this->actingAs($waiter)
            ->withSession([
                'auth_user_id' => $waiter->id_user,
                'auth_user' => $waiter->name,
                'auth_name' => $waiter->name,
                'auth_level' => $waiter->level,
            ])
            ->get(route('waiter.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText('Sudah Check Out (17:00)');
        $response->assertSeeText('Absensi Selesai');
    }

    public function test_attendance_check_in_via_api()
    {
        $waiter = User::where('name', 'waiter')->first();
        $tanggal = now()->toDateString();

        $response = $this->actingAs($waiter)
            ->withSession([
                'auth_user_id' => $waiter->id_user,
                'auth_user' => $waiter->name,
                'auth_name' => $waiter->name,
                'auth_level' => $waiter->level,
            ])
            ->postJson(route('attendance.checkIn'), [
                'scan_value' => 'Hand Gesture 2 Jari Verified',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'ok' => true,
            'message' => 'Check-in berhasil',
        ]);

        $this->assertDatabaseHas('absensis', [
            'id_user' => $waiter->id_user,
            'tanggal' => $tanggal,
            'status' => 'masuk',
        ]);
    }

    public function test_attendance_check_out_via_api()
    {
        $waiter = User::where('name', 'waiter')->first();
        $tanggal = now()->toDateString();

        // Pre-create check-in
        Absensi::create([
            'id_user' => $waiter->id_user,
            'tanggal' => $tanggal,
            'jam_masuk' => '08:00:00',
            'status' => 'masuk',
        ]);

        $response = $this->actingAs($waiter)
            ->withSession([
                'auth_user_id' => $waiter->id_user,
                'auth_user' => $waiter->name,
                'auth_name' => $waiter->name,
                'auth_level' => $waiter->level,
            ])
            ->postJson(route('attendance.checkOut'), [
                'scan_value' => 'Hand Gesture 2 Jari Verified',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'ok' => true,
            'message' => 'Check-out berhasil',
        ]);

        $this->assertDatabaseHas('absensis', [
            'id_user' => $waiter->id_user,
            'tanggal' => $tanggal,
            'status' => 'keluar',
        ]);
    }
}
