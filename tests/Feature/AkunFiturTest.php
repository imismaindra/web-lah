<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AkunFiturTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'penulis']);
    }

    private function makeUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'is_approved' => true,
            'email_verified_at' => now(),
        ], $overrides));
    }

    private function makeArtikel(): Artikel
    {
        $kategori = Kategori::create(['nama' => 'Perang', 'slug' => 'perang']);

        return Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $this->makeUser()->id,
            'judul' => 'Artikel Test',
            'ringkasan' => 'Ringkasan singkat',
            'konten' => '<p>Konten artikel.</p>',
            'status' => 'published',
        ]);
    }

    public function test_guest_dialihkan_ke_login_panel_saat_akses_panel(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('panel.login'));
        $this->get('/profil')->assertRedirect(route('login'));
    }

    public function test_pengguna_tanpa_role_tidak_bisa_akses_panel(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/admin/kategori')->assertForbidden();
        $this->actingAs($user)->get('/profil')->assertOk();
    }

    public function test_penulis_bisa_akses_panel_tapi_tidak_menu_admin(): void
    {
        $user = $this->makeUser();
        $user->assignRole('penulis');

        $this->actingAs($user)->get('/admin/dashboard')->assertOk();
        $this->actingAs($user)->get('/admin/kategori')->assertForbidden();
    }

    public function test_admin_bisa_akses_semua_menu_panel(): void
    {
        $user = $this->makeUser();
        $user->assignRole('admin');

        $this->actingAs($user)->get('/admin/dashboard')->assertOk();
        $this->actingAs($user)->get('/admin/kategori')->assertOk();
        $this->actingAs($user)->get('/admin/komentar')->assertOk();
    }

    public function test_login_pengguna_diarahkan_ke_profil(): void
    {
        $user = $this->makeUser();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('profil.edit'));
    }

    public function test_admin_dan_penulis_diblokir_di_halaman_login_pembaca(): void
    {
        $admin = $this->makeUser();
        $admin->assignRole('admin');

        $penulis = $this->makeUser();
        $penulis->assignRole('penulis');

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('panel.login'));

        $this->post('/login', ['email' => $penulis->email, 'password' => 'password'])
            ->assertRedirect(route('panel.login'));

        $this->assertGuest();
    }

    public function test_pengguna_diblokir_di_halaman_login_panel(): void
    {
        $user = $this->makeUser();

        $this->post('/panel/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_panel_admin_dan_penulis_diarahkan_ke_dashboard(): void
    {
        $admin = $this->makeUser();
        $admin->assignRole('admin');

        $penulis = $this->makeUser();
        $penulis->assignRole('penulis');

        $this->post('/panel/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->post('/panel/login', ['email' => $penulis->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_yang_sudah_login_dibawa_ke_destinasinya_saat_membuka_halaman_login(): void
    {
        $pengguna = $this->makeUser();
        $this->actingAs($pengguna);

        $this->get('/login')->assertRedirect(route('profil.edit'));
        $this->get('/panel/login')->assertRedirect(route('profil.edit'));

        $penulis = $this->makeUser();
        $penulis->assignRole('penulis');
        $this->actingAs($penulis);

        $this->get('/login')->assertRedirect(route('admin.dashboard'));
        $this->get('/panel/login')->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_user_belum_verifikasi_diarahkan_ke_notice(): void
    {
        $user = User::factory()->unverified()->create(['is_approved' => true]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('verification.notice'));
    }

    public function test_login_user_belum_disetujui_ditolak(): void
    {
        $user = User::factory()->create(['is_approved' => false]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
    }

    public function test_verifikasi_email_lewat_tautan(): void
    {
        $user = User::factory()->unverified()->create(['is_approved' => true]);

        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->get($url)->assertRedirect(route('login'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_profil_bisa_diubah_dan_ganti_kata_sandi(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $this->put('/profil', ['name' => 'Nama Baru', 'email' => $user->email])
            ->assertSessionHasNoErrors();

        $this->assertEquals('Nama Baru', $user->fresh()->name);

        $this->put('/profil/password', [
            'current_password' => 'password',
            'password' => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('passwordbaru123', $user->fresh()->password));
    }

    public function test_pengunjung_bisa_berkomentar_dengan_nama(): void
    {
        $artikel = $this->makeArtikel();

        $this->post('/artikel/'.$artikel->slug.'/komentar', ['isi' => 'Komentar bagus', 'nama' => 'Tamu'])
            ->assertRedirect();

        $this->assertDatabaseHas('komentars', [
            'artikel_id' => $artikel->id,
            'isi' => 'Komentar bagus',
            'nama' => 'Tamu',
            'user_id' => null,
        ]);
    }

    public function test_pengunjung_tanpa_nama_gagal_berkomentar(): void
    {
        $artikel = $this->makeArtikel();

        $this->post('/artikel/'.$artikel->slug.'/komentar', ['isi' => 'Tanpa nama'])
            ->assertSessionHasErrors('nama');
    }

    public function test_user_login_bisa_berkomentar_dan_membalas(): void
    {
        $artikel = $this->makeArtikel();
        $user = $this->makeUser();
        $this->actingAs($user);

        $this->post('/artikel/'.$artikel->slug.'/komentar', ['isi' => 'Komentar user'])
            ->assertRedirect();

        $parent = Komentar::where('artikel_id', $artikel->id)->firstOrFail();

        $this->post('/artikel/'.$artikel->slug.'/komentar', [
            'isi' => 'Balasan',
            'parent_id' => $parent->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('komentars', ['parent_id' => $parent->id, 'isi' => 'Balasan']);
    }

    public function test_reaksi_suka_bisa_ditoggle(): void
    {
        $artikel = $this->makeArtikel();
        $user = $this->makeUser();
        $this->actingAs($user);

        $this->post('/artikel/'.$artikel->slug.'/reaksi', [], ['Accept' => 'application/json'])
            ->assertJson(['active' => true, 'count' => 1]);

        $this->post('/artikel/'.$artikel->slug.'/reaksi', [], ['Accept' => 'application/json'])
            ->assertJson(['active' => false, 'count' => 0]);
    }

    public function test_bookmark_bisa_ditoggle_dan_dilihat_di_halaman_tersimpan(): void
    {
        $artikel = $this->makeArtikel();
        $user = $this->makeUser();
        $this->actingAs($user);

        $this->post('/artikel/'.$artikel->slug.'/bookmark', [], ['Accept' => 'application/json'])
            ->assertJson(['bookmarked' => true]);

        $this->get('/bookmark')->assertOk()->assertSee($artikel->judul);

        $this->post('/artikel/'.$artikel->slug.'/bookmark', [], ['Accept' => 'application/json'])
            ->assertJson(['bookmarked' => false]);
    }

    public function test_halaman_artikel_menampilkan_komentar(): void
    {
        $artikel = $this->makeArtikel();
        $user = $this->makeUser();
        $this->actingAs($user);

        $this->get('/artikel/'.$artikel->slug)->assertOk()->assertSee('Komentar');
    }
}
