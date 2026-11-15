<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Kategori;
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
}
