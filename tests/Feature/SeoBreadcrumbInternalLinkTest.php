<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Era;
use App\Models\Kategori;
use App\Models\Topik;
use App\Models\User;
use App\Services\InternalLinker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoBreadcrumbInternalLinkTest extends TestCase
{
    use RefreshDatabase;

    private function createArtikel(array $attributes = []): Artikel
    {
        $kategori = Kategori::firstOrCreate(
            ['slug' => 'seo-regression-test'],
            ['nama' => 'SEO Regression Test', 'deskripsi' => 'Kategori pengujian SEO.']
        );
        $author = User::factory()->create();

        return Artikel::create(array_merge([
            'kategori_id' => $kategori->id,
            'user_id' => $author->id,
            'judul' => 'Artikel Pengujian SEO',
            'konten' => '<p>Konten pengujian internal linking.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    public function test_article_breadcrumb_visible_and_jsonld_are_consistent(): void
    {
        $artikel = $this->createArtikel([
            'judul' => 'Judul Pengujian Breadcrumb',
        ]);

        $response = $this->get(route('artikel.show', $artikel));

        $response->assertOk()
            ->assertSee('<span itemprop="name">Beranda</span>', false)
            ->assertSee('<span itemprop="name">Artikel</span>', false)
            ->assertSee('<span itemprop="name">SEO Regression Test</span>', false)
            ->assertSee('<span itemprop="name">Judul Pengujian Breadcrumb</span>', false)
            ->assertSee('"name":"Artikel"', false)
            ->assertSee('"name":"SEO Regression Test"', false)
            ->assertSee('"name":"Judul Pengujian Breadcrumb"', false);
    }

    public function test_internal_linker_only_links_to_existing_published_articles(): void
    {
        $targetSlug = 'mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi';
        $this->createArtikel([
            'judul' => 'Keruntuhan Kekaisaran Romawi',
            'slug' => $targetSlug,
        ]);

        $html = '<p>Kekaisaran Romawi menjadi salah satu pembahasan dalam artikel ini.</p>';
        $result = InternalLinker::link($html, new Artikel(['slug' => 'artikel-sumber']));

        $this->assertStringContainsString($targetSlug, $result);
    }

    public function test_internal_linker_omits_draft_and_missing_article_targets(): void
    {
        $draftSlug = 'mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi';
        $this->createArtikel([
            'judul' => 'Artikel Romawi Draft',
            'slug' => $draftSlug,
            'status' => 'draft',
            'published_at' => null,
        ]);

        $html = '<p>Kekaisaran Romawi dan netralitas Swiss dibahas di sini.</p>';
        $result = InternalLinker::link($html, new Artikel(['slug' => 'artikel-sumber']));

        $this->assertStringNotContainsString($draftSlug, $result);
        $this->assertStringNotContainsString(
            'mengapa-swiss-tidak-diserang-jerman-nazi',
            $result
        );
    }

    public function test_kategori_index_page_is_accessible_and_renders_breadcrumbs(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Peradaban Kuno',
            'slug' => 'peradaban-kuno',
            'deskripsi' => 'Arsip peradaban kuno dunia.',
        ]);

        $this->createArtikel([
            'kategori_id' => $kategori->id,
            'judul' => 'Misteri Piramida Giza',
        ]);

        $response = $this->get(route('kategori.index'));

        $response->assertOk()
            ->assertSee('Kategori Sejarah')
            ->assertSee('Peradaban Kuno')
            ->assertSee('Misteri Piramida Giza')
            ->assertSee('aria-label="Breadcrumb"', false);
    }

    public function test_kategori_show_has_hierarchical_breadcrumb_with_index(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Perang Dunia',
            'slug' => 'perang-dunia',
            'deskripsi' => 'Kategori perang dunia.',
        ]);

        $response = $this->get(route('kategori.show', $kategori));

        $response->assertOk()
            ->assertSee(route('kategori.index'))
            ->assertSee('"name":"Kategori"', false)
            ->assertSee('"name":"Perang Dunia"', false);
    }

    public function test_era_and_topik_show_have_breadcrumbs_and_jsonld(): void
    {
        $era = Era::create([
            'nama' => 'Era Klasik',
            'slug' => 'era-klasik',
            'periode' => '800 SM - 500 M',
            'urutan' => 1,
        ]);

        $topik = Topik::create([
            'nama' => 'Kekaisaran Romawi',
            'slug' => 'kekaisaran-romawi',
            'urutan' => 1,
        ]);

        $eraResponse = $this->get(route('era.show', $era));
        $eraResponse->assertOk()
            ->assertSee('Era Klasik')
            ->assertSee(route('artikel.index'))
            ->assertSee('"name":"Era Klasik"', false);

        $topikResponse = $this->get(route('topik.show', $topik));
        $topikResponse->assertOk()
            ->assertSee('Kekaisaran Romawi')
            ->assertSee(route('artikel.index'))
            ->assertSee('"name":"Kekaisaran Romawi"', false);
    }

    public function test_header_and_footer_link_to_kategori_index_and_subsections(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee(route('kategori.index'))
            ->assertSee('Indeks Kategori')
            ->assertSee('Linimasa Era')
            ->assertSee(route('feed'))
            ->assertSee(route('sitemap'));
    }
}
