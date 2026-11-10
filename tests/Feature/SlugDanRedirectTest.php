<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\UrlRedirect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SlugDanRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'penulis']);
    }

    private function makeUser(string $role = 'admin'): User
    {
        $user = User::factory()->create([
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function createKategori(array $attributes = []): Kategori
    {
        return Kategori::create(array_merge([
            'nama' => 'Perang Dunia',
            'slug' => 'perang-dunia',
            'deskripsi' => 'Kategori sejarah perang',
        ], $attributes));
    }

    public function test_slug_digenerate_otomatis_dari_judul_jika_slug_kosong(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser();

        $artikel = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Perjanjian Giyanti 1755',
            'konten' => 'Isi sejarah perjanjian giyanti lengkap.',
            'status' => 'published',
        ]);

        $this->assertEquals('perjanjian-giyanti-1755', $artikel->slug);
    }

    public function test_custom_slug_diterapkan_jika_diisi_secara_manual(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser();

        $artikel = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Perjanjian Giyanti 1755 dan Pembagian Wilayah Mataram',
            'slug' => 'perjanjian-giyanti-ringkas',
            'konten' => 'Isi ringkas perjanjian giyanti.',
            'status' => 'published',
        ]);

        $this->assertEquals('perjanjian-giyanti-ringkas', $artikel->slug);
    }

    public function test_tabrakan_slug_diberi_suffix_angka_otomatis(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser();

        $art1 = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Perang Diponegoro',
            'konten' => 'Artikel pertama perang diponegoro.',
            'status' => 'published',
        ]);

        $art2 = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Perang Diponegoro',
            'konten' => 'Artikel kedua perang diponegoro judul sama.',
            'status' => 'published',
        ]);

        $art3 = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Perang Diponegoro',
            'konten' => 'Artikel ketiga perang diponegoro judul sama.',
            'status' => 'published',
        ]);

        $this->assertEquals('perang-diponegoro', $art1->slug);
        $this->assertEquals('perang-diponegoro-1', $art2->slug);
        $this->assertEquals('perang-diponegoro-2', $art3->slug);
    }

    public function test_slug_unik_memperhitungkan_artikel_yang_di_soft_delete(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser();

        $art1 = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Runtuhnya Majapahit',
            'konten' => 'Artikel lama runtuhnya majapahit.',
            'status' => 'published',
        ]);

        $this->assertEquals('runtuhnya-majapahit', $art1->slug);

        $art1->delete();
        $this->assertSoftDeleted('artikels', ['id' => $art1->id]);

        $art2 = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Runtuhnya Majapahit',
            'konten' => 'Artikel baru dengan judul sama setelah yang lama dihapus.',
            'status' => 'published',
        ]);

        $this->assertEquals('runtuhnya-majapahit-1', $art2->slug);
    }

    public function test_mengubah_judul_tanpa_mengubah_slug_tidak_merusak_slug(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser();

        $artikel = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Judul Awal Artikel',
            'konten' => 'Isi artikel.',
            'status' => 'published',
        ]);

        $slugAwal = $artikel->slug;
        $this->assertEquals('judul-awal-artikel', $slugAwal);

        $artikel->update([
            'judul' => 'Judul Baru Setelah Revisi Typo',
        ]);

        $artikel->refresh();
        $this->assertEquals('judul-awal-artikel', $artikel->slug);
        $this->assertEquals('Judul Baru Setelah Revisi Typo', $artikel->judul);
    }

    public function test_mengubah_slug_secara_eksplisit_mencatat_redirect_301(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser();

        $artikel = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Revolusi Industri Inggris',
            'slug' => 'revolusi-industri',
            'konten' => 'Isi artikel revolusi industri.',
            'status' => 'published',
        ]);

        $artikel->slug = 'sejarah-revolusi-industri-britania';
        $artikel->save();

        $artikel->refresh();
        $this->assertEquals('sejarah-revolusi-industri-britania', $artikel->slug);

        $this->assertDatabaseHas('url_redirects', [
            'source_path' => 'artikel/revolusi-industri',
            'target_path' => 'artikel/sejarah-revolusi-industri-britania',
            'status_code' => 301,
        ]);
    }

    public function test_mengakses_slug_lama_mengembalikan_redirect_301_ke_slug_baru(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser();

        $artikel = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Kekaisaran Romawi Kuno',
            'slug' => 'romawi-kuno',
            'konten' => 'Konten romawi kuno.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $artikel->slug = 'sejarah-kekaisaran-romawi-kuno';
        $artikel->save();

        $response = $this->get('/artikel/romawi-kuno');

        $response->assertStatus(301);
        $response->assertRedirect('/artikel/sejarah-kekaisaran-romawi-kuno');

        $redirect = UrlRedirect::where('source_path', 'artikel/romawi-kuno')->first();
        $this->assertNotNull($redirect);
        $this->assertGreaterThan(0, $redirect->hits);
    }

    public function test_fallback_route_mengarahkan_url_redirect(): void
    {
        UrlRedirect::record('url-lama-web', 'artikel', 301);

        $response = $this->get('/url-lama-web');

        $response->assertStatus(301);
        $response->assertRedirect('/artikel');
    }

    public function test_chained_redirects_otomatis_dipersingkat(): void
    {
        UrlRedirect::record('artikel/slug-a', 'artikel/slug-b', 301);
        UrlRedirect::record('artikel/slug-b', 'artikel/slug-c', 301);

        $this->assertDatabaseHas('url_redirects', [
            'source_path' => 'artikel/slug-a',
            'target_path' => 'artikel/slug-c',
        ]);
        $this->assertDatabaseHas('url_redirects', [
            'source_path' => 'artikel/slug-b',
            'target_path' => 'artikel/slug-c',
        ]);
    }

    public function test_admin_bisa_membuat_artikel_dengan_custom_slug_lewat_form(): void
    {
        $admin = $this->makeUser('admin');
        $kategori = $this->createKategori();

        $response = $this->actingAs($admin)->post('/admin/artikel', [
            'kategori_id' => $kategori->id,
            'judul' => 'Perang Padri di Minangkabau',
            'slug' => 'perang-padri-singkat',
            'konten' => 'Isi artikel perang padri.',
            'status' => 'draft',
        ]);

        $response->assertRedirect('/admin/artikel');
        $this->assertDatabaseHas('artikels', [
            'judul' => 'Perang Padri di Minangkabau',
            'slug' => 'perang-padri-singkat',
        ]);
    }
}
