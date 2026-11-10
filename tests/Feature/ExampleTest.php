<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_halaman_publik_utama_dapat_diakses(): void
    {
        $routes = [
            '/',
            '/artikel',
            '/kontak',
            '/kebijakan-privasi',
            '/tentang',
            '/cari',
            '/sitemap.xml',
            '/feed',
            '/login',
            '/register',
            '/forgot-password',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertOk();
        }
    }

    public function test_seluruh_halaman_admin_dapat_diakses_oleh_admin(): void
    {
        Role::firstOrCreate(['name' => 'admin']);

        $admin = User::factory()->create([
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $adminRoutes = [
            '/admin/dashboard',
            '/admin/artikel',
            '/admin/artikel/create',
            '/admin/kategori',
            '/admin/kategori/create',
            '/admin/era',
            '/admin/era/create',
            '/admin/topik',
            '/admin/topik/create',
            '/admin/penulis',
            '/admin/penulis/create',
            '/admin/komentar',
            '/admin/kontak',
            '/admin/newsletter',
            '/admin/newsletter/create',
            '/admin/pengguna',
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertOk();
        }
    }
}
