<?php

namespace Tests\Feature;

use App\Jobs\SendNewsletterCampaignJob;
use App\Mail\NewsletterMail;
use App\Mail\VerifyEmailMail;
use App\Models\Artikel;
use App\Models\Bookmark;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\KontakPesan;
use App\Models\Subscriber;
use App\Models\User;
use App\Services\ImageOptimizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuboptimalFiturTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'penulis']);
        Role::firstOrCreate(['name' => 'pembaca']);
    }

    private function makeUser(string $role = 'penulis', array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'is_approved' => true,
            'email_verified_at' => now(),
        ], $attributes));

        $user->assignRole($role);

        return $user;
    }

    private function createKategori(string $slug = 'sejarah-dunia', string $nama = 'Sejarah Dunia'): Kategori
    {
        return Kategori::firstOrCreate(['slug' => $slug], ['nama' => $nama, 'deskripsi' => 'Deskripsi']);
    }

    private function createArtikel(array $attributes = []): Artikel
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser('penulis');

        return Artikel::create(array_merge([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Judul Artikel Uji Coba',
            'konten' => 'Konten artikel lengkap untuk pengujian sistem.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_bookmark_hanya_menampilkan_artikel_yang_published(): void
    {
        $user = $this->makeUser('pembaca');

        $publishedArtikel = $this->createArtikel([
            'judul' => 'Artikel Terbit Untuk Bookmark',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $draftArtikel = $this->createArtikel([
            'judul' => 'Artikel Draf Yang Tidak Boleh Muncul',
            'status' => 'draft',
            'published_at' => null,
        ]);

        Bookmark::create(['user_id' => $user->id, 'artikel_id' => $publishedArtikel->id]);
        Bookmark::create(['user_id' => $user->id, 'artikel_id' => $draftArtikel->id]);

        $response = $this->actingAs($user)->get(route('bookmark.index'));

        $response->assertOk();
        $response->assertSee('Artikel Terbit Untuk Bookmark');
        $response->assertDontSee('Artikel Draf Yang Tidak Boleh Muncul');
    }

    public function test_perubahan_email_mereset_verifikasi_dan_mengirim_notifikasi(): void
    {
        Mail::fake();

        $user = $this->makeUser('pembaca', [
            'email' => 'lama@example.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->put(route('profil.update'), [
                'name' => $user->name,
                'email' => 'baru@example.com',
            ])
            ->assertRedirect();

        $user->refresh();

        $this->assertSame('baru@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
        Mail::assertSent(VerifyEmailMail::class, function ($mail) {
            return $mail->hasTo('baru@example.com');
        });
    }

    public function test_pesan_kontak_disimpan_dan_honeypot_mencegah_spam(): void
    {
        Mail::fake();

        // Valid submission
        $response = $this->post(route('kontak.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'subject' => 'Tanya Arsip',
            'message' => 'Halo, saya ingin bertanya tentang arsip VOC.',
            'website' => '', // honeypot kosong
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('kontak_pesans', [
            'email' => 'budi@example.com',
            'subject' => 'Tanya Arsip',
        ]);

        // Honeypot spam submission
        $this->post(route('kontak.store'), [
            'name' => 'Spambot',
            'email' => 'bot@example.com',
            'subject' => 'Viagra Discount',
            'message' => 'Spam content here',
            'website' => 'http://spam.ru', // honeypot terisi
        ]);

        $this->assertDatabaseMissing('kontak_pesans', [
            'email' => 'bot@example.com',
        ]);
    }

    public function test_admin_dapat_melihat_dan_mengelola_pesan_kontak(): void
    {
        $admin = $this->makeUser('admin');

        $pesan = KontakPesan::create([
            'name' => 'Ahmad',
            'email' => 'ahmad@example.com',
            'subject' => 'Pertanyaan',
            'message' => 'Isi pesan teks lengkap',
            'is_read' => false,
        ]);

        // List
        $this->actingAs($admin)
            ->get(route('admin.kontak.index'))
            ->assertOk()
            ->assertSee('Ahmad');

        // Show (otomatis ditandai dibaca)
        $this->actingAs($admin)
            ->get(route('admin.kontak.show', $pesan))
            ->assertOk()
            ->assertSee('Isi pesan teks lengkap');

        $this->assertTrue($pesan->fresh()->is_read);

        // Toggle read
        $this->actingAs($admin)
            ->post(route('admin.kontak.toggle-read', $pesan))
            ->assertRedirect();

        $this->assertFalse($pesan->fresh()->is_read);

        // Delete
        $this->actingAs($admin)
            ->delete(route('admin.kontak.destroy', $pesan))
            ->assertRedirect();

        $this->assertDatabaseMissing('kontak_pesans', ['id' => $pesan->id]);
    }

    public function test_newsletter_campaign_disimpan_dan_job_didispatch(): void
    {
        Queue::fake();

        $admin = $this->makeUser('admin');
        Subscriber::create(['email' => 'reader@example.com', 'token' => 'tok123']);

        $response = $this->actingAs($admin)->post(route('admin.newsletter.store'), [
            'subject' => 'Edisi Sejarah Nusantara',
            'message' => 'Isi materi buletin mingguan.',
        ]);

        $response->assertRedirect(route('admin.newsletter.index'));

        $this->assertDatabaseHas('newsletters', [
            'subject' => 'Edisi Sejarah Nusantara',
            'user_id' => $admin->id,
        ]);

        Queue::assertPushed(SendNewsletterCampaignJob::class);
    }

    public function test_newsletter_uji_coba_mengirim_email_ke_admin(): void
    {
        Mail::fake();

        $admin = $this->makeUser('admin', ['email' => 'admin@weblah.com']);

        $response = $this->actingAs($admin)->post(route('admin.newsletter.test-send'), [
            'subject' => 'Pratinjau Buletin',
            'message' => 'Konten tes.',
        ]);

        $response->assertSessionHas('success');

        Mail::assertSent(NewsletterMail::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });
    }

    public function test_bulk_action_moderasi_komentar(): void
    {
        $admin = $this->makeUser('admin');
        $artikel = $this->createArtikel();

        $k1 = Komentar::create([
            'artikel_id' => $artikel->id,
            'nama' => 'User 1',
            'isi' => 'Komentar 1',
            'status' => 'pending',
        ]);
        $k2 = Komentar::create([
            'artikel_id' => $artikel->id,
            'nama' => 'User 2',
            'isi' => 'Komentar 2',
            'status' => 'pending',
        ]);
        $k3 = Komentar::create([
            'artikel_id' => $artikel->id,
            'nama' => 'User 3',
            'isi' => 'Komentar 3',
            'status' => 'pending',
        ]);

        // Bulk approve k1 & k2
        $this->actingAs($admin)
            ->post(route('admin.komentar.bulk'), [
                'action' => 'approve',
                'ids' => [$k1->id, $k2->id],
            ])
            ->assertRedirect();

        $this->assertSame('published', $k1->fresh()->status);
        $this->assertSame('published', $k2->fresh()->status);
        $this->assertSame('pending', $k3->fresh()->status);

        // Bulk delete k3
        $this->actingAs($admin)
            ->post(route('admin.komentar.bulk'), [
                'action' => 'delete',
                'ids' => [$k3->id],
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('komentars', ['id' => $k3->id]);
    }

    public function test_admin_pengguna_search_filter_dan_update_role(): void
    {
        $admin = $this->makeUser('admin');
        $targetUser = $this->makeUser('penulis', ['name' => 'Sutan Sjahrir', 'email' => 'sutan@example.com']);

        // Search list
        $this->actingAs($admin)
            ->get(route('admin.pengguna.index', ['search' => 'Sutan']))
            ->assertOk()
            ->assertSee('Sutan Sjahrir');

        // Update role to pembaca (syncRoles empty)
        $this->actingAs($admin)
            ->put(route('admin.pengguna.role', $targetUser), [
                'role' => 'pembaca',
            ])
            ->assertRedirect();

        $this->assertFalse($targetUser->fresh()->hasRole('penulis'));

        // Update role back to penulis
        $this->actingAs($admin)
            ->put(route('admin.pengguna.role', $targetUser), [
                'role' => 'penulis',
            ])
            ->assertRedirect();

        $this->assertTrue($targetUser->fresh()->hasRole('penulis'));
    }

    public function test_search_suggest_api_mengembalikan_json_rekomendasi(): void
    {
        $this->createArtikel([
            'judul' => 'Perang Diponegoro dan Strategi Benteng Stelsel',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->getJson('/api/search/suggest?q=Diponegoro');

        $response->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'judul', 'slug', 'url', 'kategori', 'reading_time'],
            ])
            ->assertJsonFragment(['judul' => 'Perang Diponegoro dan Strategi Benteng Stelsel']);
    }

    public function test_artikel_accessor_srcset_dan_service_image_optimizer(): void
    {
        $kategori = $this->createKategori();
        $author = $this->makeUser('penulis');

        $artikelWithResponsive = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Uji Coba Gambar Responsif',
            'konten' => 'Konten uji coba gambar',
            'gambar' => 'artikels/foto-perang-large.webp',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $artikelWithoutImage = Artikel::create([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Uji Coba Tanpa Gambar',
            'konten' => 'Konten tanpa gambar',
            'gambar' => null,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->assertNull($artikelWithoutImage->srcset);

        $srcset = $artikelWithResponsive->srcset;
        $this->assertNotNull($srcset);
        $this->assertStringContainsString('foto-perang-thumb.webp 400w', $srcset);
        $this->assertStringContainsString('foto-perang-medium.webp 800w', $srcset);
        $this->assertStringContainsString('foto-perang-large.webp 1200w', $srcset);

        // Test non-large image format returns null
        $nonLargeSrcset = ImageOptimizer::getSrcset('artikels/biasa.jpg');
        $this->assertNull($nonLargeSrcset);
    }
}
