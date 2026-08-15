<?php

namespace Tests\Feature;

use App\Models\Penulis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPenulisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
    }

    private function makeAdmin(): User
    {
        $admin = User::factory()->create(['is_approved' => true, 'email_verified_at' => now()]);
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_admin_bisa_menambahkan_penulis_dari_user_yang_ada_tanpa_bio_dan_website(): void
    {
        $user = User::factory()->create(['is_approved' => true, 'email_verified_at' => now()]);

        $this->actingAs($this->makeAdmin())
            ->post('/admin/penulis', [
                'user_id' => $user->id,
                'nama' => 'Penulis Baru',
            ])
            ->assertRedirect(route('admin.penulis.index'));

        $this->assertDatabaseHas('penulis', ['user_id' => $user->id, 'nama' => 'Penulis Baru']);
        $this->assertTrue($user->fresh()->hasRole('penulis'));
    }

    public function test_admin_bisa_menambahkan_penulis_baru_dengan_akun_baru(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post('/admin/penulis', [
                'name' => 'Penulis Baru',
                'email' => 'penulis@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'nama' => 'Penulis Baru',
            ])
            ->assertRedirect(route('admin.penulis.index'));

        $this->assertDatabaseHas('penulis', ['nama' => 'Penulis Baru']);
        $this->assertSame(1, Penulis::count());

        $user = User::where('email', 'penulis@example.com')->firstOrFail();
        $this->assertTrue($user->hasRole('penulis'));
    }

    public function test_menambahkan_penulis_membuat_role_penulis_secara_otomatis(): void
    {
        $user = User::factory()->create(['is_approved' => true, 'email_verified_at' => now()]);

        $this->actingAs($this->makeAdmin())
            ->post('/admin/penulis', [
                'user_id' => $user->id,
                'nama' => 'Penulis Baru',
            ]);

        $this->assertDatabaseHas('roles', ['name' => 'penulis']);
    }
}
