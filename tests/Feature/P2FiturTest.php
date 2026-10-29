<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Pageview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class P2FiturTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'penulis']);
    }

    private function makeUser(string $role = 'penulis'): User
    {
        $user = User::factory()->create([
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function createArtikel(array $attributes = []): Artikel
    {
        $kategori = Kategori::firstOrCreate(
            ['slug' => 'sejarah-dunia'],
            ['nama' => 'Sejarah Dunia', 'deskripsi' => 'Deskripsi']
        );
        $author = $this->makeUser('penulis');

        return Artikel::create(array_merge([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Revolusi Industri dan Peradaban',
            'konten' => str_repeat('kata ', 450),
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_reading_time_dihitung_otomatis_dan_tersimpan_di_database(): void
    {
        $artikel = $this->createArtikel([
            'konten' => str_repeat('sejarah masa lalu bangsa ', 100), // 400 kata => 2 menit
        ]);

        $this->assertSame(2, $artikel->fresh()->reading_time);

        $artikel->update([
            'konten' => str_repeat('kata pendek ', 30), // 60 kata => 1 menit
        ]);

        $this->assertSame(1, $artikel->fresh()->reading_time);
    }

    public function test_pengunjung_valid_menambah_views_dan_tercatat_di_tabel_pageviews(): void
    {
        $artikel = $this->createArtikel();

        $response = $this->withServerVariables([
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
        ])->get(route('artikel.show', $artikel));

        $response->assertOk();
        $this->assertSame(1, $artikel->fresh()->views);
        $this->assertDatabaseHas('pageviews', [
            'artikel_id' => $artikel->id,
        ]);
    }

    public function test_bot_crawler_tidak_menambah_views_dan_tidak_dicatat_di_pageviews(): void
    {
        $artikel = $this->createArtikel();

        $botUserAgents = [
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'curl/7.88.1',
            'python-requests/2.31.0',
            'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        ];

        foreach ($botUserAgents as $botAgent) {
            $response = $this->withServerVariables([
                'HTTP_USER_AGENT' => $botAgent,
            ])->get(route('artikel.show', $artikel));

            $response->assertOk();
        }

        $this->assertSame(0, $artikel->fresh()->views);
        $this->assertSame(0, Pageview::count());
    }

    public function test_refresh_berulang_dalam_sesi_tidak_menggandakan_view(): void
    {
        $artikel = $this->createArtikel();

        $session = [];

        for ($i = 0; $i < 3; $i++) {
            $response = $this->withSession($session)
                ->withServerVariables([
                    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
                ])
                ->get(route('artikel.show', $artikel));

            $response->assertOk();
            $session = session()->all();
        }

        $this->assertSame(1, $artikel->fresh()->views);
        $this->assertSame(1, Pageview::where('artikel_id', $artikel->id)->count());
    }

    public function test_artikel_terjadwal_dan_draft_tidak_dapat_diakses_publik(): void
    {
        $scheduled = $this->createArtikel([
            'judul' => 'Artikel Masa Depan',
            'status' => 'scheduled',
            'published_at' => now()->addDays(3),
        ]);

        $draft = $this->createArtikel([
            'judul' => 'Artikel Masih Konsep',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->get(route('artikel.show', $scheduled))->assertNotFound();
        $this->get(route('artikel.show', $draft))->assertNotFound();
    }

    public function test_penulis_dan_admin_dapat_melihat_pratinjau_draft_dan_scheduled_tanpa_menaikkan_view(): void
    {
        $author = $this->makeUser('penulis');
        $artikel = $this->createArtikel([
            'user_id' => $author->id,
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->actingAs($author)->get(route('artikel.show', $artikel));

        $response->assertOk();
        $response->assertSee('Mode Pratinjau');
        $this->assertSame(0, $artikel->fresh()->views);
        $this->assertSame(0, Pageview::count());

        $previewResponse = $this->actingAs($author)->get(route('admin.artikel.preview', $artikel));
        $previewResponse->assertOk();
        $previewResponse->assertSee('Mode Pratinjau');
    }

    public function test_command_publish_scheduled_menerbitkan_artikel_yang_waktunya_tiba(): void
    {
        Carbon::setTestNow('2026-10-29 10:00:00');

        $artikelAkanTerbit = $this->createArtikel([
            'judul' => 'Artikel Terbit Sekarang',
            'status' => 'scheduled',
            'published_at' => now()->subMinute(),
        ]);

        $artikelBelumWaktunya = $this->createArtikel([
            'judul' => 'Artikel Terbit Nanti',
            'status' => 'scheduled',
            'published_at' => now()->addHour(),
        ]);

        $this->artisan('artikel:publish-scheduled')
            ->assertSuccessful();

        $this->assertSame('published', $artikelAkanTerbit->fresh()->status);
        $this->assertSame('scheduled', $artikelBelumWaktunya->fresh()->status);
        $this->assertTrue(Artikel::published()->where('id', $artikelAkanTerbit->id)->exists());
        $this->assertFalse(Artikel::published()->where('id', $artikelBelumWaktunya->id)->exists());

        Carbon::setTestNow();
    }

    public function test_admin_bisa_membuat_artikel_terjadwal(): void
    {
        $admin = $this->makeUser('admin');
        $kategori = Kategori::firstOrCreate(
            ['slug' => 'sejarah-kuno'],
            ['nama' => 'Sejarah Kuno', 'deskripsi' => 'Deskripsi']
        );

        $waktuJadwal = now()->addDays(2)->format('Y-m-d\TH:i');

        $response = $this->actingAs($admin)->post(route('admin.artikel.store'), [
            'kategori_id' => $kategori->id,
            'judul' => 'Penemuan Kota Kuno',
            'konten' => '<p>Konten sejarah kota kuno yang sangat panjang.</p>',
            'status' => 'scheduled',
            'published_at' => $waktuJadwal,
        ]);

        $response->assertRedirect(route('admin.artikel.index'));

        $artikel = Artikel::where('judul', 'Penemuan Kota Kuno')->firstOrFail();
        $this->assertSame('scheduled', $artikel->status);
        $this->assertNotNull($artikel->published_at);
        $this->assertSame(1, $artikel->reading_time);
    }
}

