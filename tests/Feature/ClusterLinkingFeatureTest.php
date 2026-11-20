<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\User;
use App\Services\ContentClusterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClusterLinkingFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('admin');
        Role::findOrCreate('penulis');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->kategori = Kategori::create([
            'nama' => 'Sejarah Kuno',
            'slug' => 'sejarah-kuno',
        ]);
    }

    private function createArticle(array $attributes = []): Artikel
    {
        return Artikel::create(array_merge([
            'kategori_id' => $this->kategori->id,
            'user_id' => $this->admin->id,
            'judul' => 'Judul Pengujian '.uniqid(),
            'konten' => '<p>Konten pengujian sejarah.</p>',
            'status' => 'published',
            'published_at' => now(),
        ], $attributes));
    }

    public function test_can_identify_pillar_and_supporting_articles(): void
    {
        $pillar = $this->createArticle([
            'judul' => 'Peradaban Majapahit Lengkap',
            'is_pillar' => true,
        ]);

        $supporting = $this->createArticle([
            'judul' => 'Armada Laut Majapahit',
            'pillar_id' => $pillar->id,
            'is_pillar' => false,
        ]);

        $standard = $this->createArticle([
            'judul' => 'Fakta Revolusi Industri',
            'is_pillar' => false,
            'pillar_id' => null,
        ]);

        $this->assertTrue($pillar->isPillar());
        $this->assertFalse($pillar->isSupporting());

        $this->assertFalse($supporting->isPillar());
        $this->assertTrue($supporting->isSupporting());
        $this->assertEquals($pillar->id, $supporting->pillar->id);
        $this->assertTrue($pillar->supportingArticles->contains('id', $supporting->id));

        $this->assertFalse($standard->isPillar());
        $this->assertFalse($standard->isSupporting());
    }

    public function test_detects_when_supporting_article_fails_to_link_back_to_pillar(): void
    {
        $pillar = $this->createArticle([
            'judul' => 'Kekaisaran Romawi Kuno',
            'slug' => 'kekaisaran-romawi-kuno',
            'is_pillar' => true,
        ]);

        $unlinkedSupporting = $this->createArticle([
            'judul' => 'Legiun Romawi di Britania',
            'slug' => 'legiun-romawi-di-britania',
            'pillar_id' => $pillar->id,
            'konten' => '<p>Artikel ini membahas legiun di Britania tanpa menautkan ke pilar.</p>',
        ]);

        $linkedSupporting = $this->createArticle([
            'judul' => 'Taktik Perang Caesar',
            'slug' => 'taktik-perang-caesar',
            'pillar_id' => $pillar->id,
            'konten' => '<p>Pelajari selengkapnya di <a href="/artikel/kekaisaran-romawi-kuno">Kekaisaran Romawi Kuno</a>.</p>',
        ]);

        $service = app(ContentClusterService::class);

        $this->assertFalse($service->hasPillarBacklink($unlinkedSupporting));
        $this->assertTrue($service->hasPillarBacklink($linkedSupporting));

        $audit = $service->getClusterAudit();
        $this->assertEquals(1, $audit['total_pillars']);
        $this->assertEquals(2, $audit['total_supporting']);
        $this->assertEquals(1, $audit['missing_backlink_count']);
        $this->assertEquals(1, $audit['healthy_count']);
        $this->assertTrue($audit['missing_backlinks']->contains('id', $unlinkedSupporting->id));
        $this->assertFalse($audit['missing_backlinks']->contains('id', $linkedSupporting->id));
    }

    public function test_syncs_internal_links_automatically_when_article_saved(): void
    {
        $targetA = $this->createArticle([
            'judul' => 'Target A',
            'slug' => 'target-a',
        ]);

        $targetB = $this->createArticle([
            'judul' => 'Target B',
            'slug' => 'target-b',
        ]);

        $source = $this->createArticle([
            'judul' => 'Artikel Sumber',
            'konten' => '<p>Baca juga <a href="/artikel/target-a">Target A</a> serta <a href="http://localhost:8000/artikel/target-b">Target B</a>.</p>',
        ]);

        $this->assertTrue($source->outgoingLinks->contains('id', $targetA->id));
        $this->assertTrue($source->outgoingLinks->contains('id', $targetB->id));
        $this->assertEquals(2, $source->outgoingLinks()->count());
    }

    public function test_cluster_admin_pages_and_api_endpoints_work_properly(): void
    {
        $pillar = $this->createArticle([
            'judul' => 'Pilar Sejarah Nusantara',
            'slug' => 'pilar-sejarah-nusantara',
            'is_pillar' => true,
        ]);

        $supporting = $this->createArticle([
            'judul' => 'Sriwijaya Pusat Bahari',
            'slug' => 'sriwijaya-pusat-bahari',
            'pillar_id' => $pillar->id,
            'konten' => '<p>Konten tanpa backlink.</p>',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.kluster.index'));
        $response->assertOk()
            ->assertSee('Arsitektur Kluster')
            ->assertSee('Sriwijaya Pusat Bahari')
            ->assertSee('Pilar Sejarah Nusantara');

        $graphResponse = $this->actingAs($this->admin)->getJson(route('admin.kluster.graph-data'));
        $graphResponse->assertOk()
            ->assertJsonStructure(['nodes', 'edges']);

        $searchResponse = $this->actingAs($this->admin)->getJson(route('admin.artikel.search-links', [
            'q' => 'Sriwijaya',
        ]));
        $searchResponse->assertOk()
            ->assertJsonFragment(['slug' => 'sriwijaya-pusat-bahari']);

        $syncResponse = $this->actingAs($this->admin)->post(route('admin.kluster.sync'));
        $syncResponse->assertRedirect(route('admin.kluster.index'));
    }

    public function test_can_create_and_update_articles_with_pillar_and_supporting_roles(): void
    {
        $pillar = $this->createArticle([
            'judul' => 'Pilar Purba',
            'is_pillar' => true,
        ]);

        $storeResponse = $this->actingAs($this->admin)->post(route('admin.artikel.store'), [
            'judul' => 'Kajian Fosil Sangiran',
            'kategori_id' => $this->kategori->id,
            'status' => 'published',
            'konten' => '<p>Kajian mendalam tentang fosil purba.</p>',
            'is_pillar' => 0,
            'pillar_id' => $pillar->id,
        ]);

        $storeResponse->assertRedirect(route('admin.artikel.index'));

        $created = Artikel::where('judul', 'Kajian Fosil Sangiran')->first();
        $this->assertNotNull($created);
        $this->assertFalse($created->isPillar());
        $this->assertEquals($pillar->id, $created->pillar_id);

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.artikel.update', $created), [
            'judul' => 'Kajian Fosil Sangiran Lengkap (Pilar)',
            'kategori_id' => $this->kategori->id,
            'status' => 'published',
            'konten' => '<p>Diperbarui menjadi pilar mandiri.</p>',
            'is_pillar' => 1,
            'pillar_id' => $pillar->id, // Should be cleared to null because is_pillar=1
        ]);

        $updateResponse->assertRedirect(route('admin.artikel.index'));
        $created->refresh();
        $this->assertTrue($created->isPillar());
        $this->assertNull($created->pillar_id);
    }

    public function test_artikel_index_filters_by_cluster(): void
    {
        $pillar = $this->createArticle([
            'judul' => 'Pilar Perang Dingin',
            'is_pillar' => true,
        ]);

        $unlinked = $this->createArticle([
            'judul' => 'Krisis Rudal Kuba',
            'pillar_id' => $pillar->id,
            'konten' => '<p>Tanpa tautan pilar</p>',
        ]);

        $linked = $this->createArticle([
            'judul' => 'Tembok Berlin',
            'slug' => 'tembok-berlin',
            'pillar_id' => $pillar->id,
            'konten' => '<p>Tautan ke <a href="/artikel/'.$pillar->slug.'">Pilar</a></p>',
        ]);

        // Filter pillar
        $respPillar = $this->actingAs($this->admin)->get(route('admin.artikel.index', ['cluster' => 'pillar']));
        $respPillar->assertOk();
        $this->assertTrue($respPillar->viewData('artikels')->contains('id', $pillar->id));
        $this->assertFalse($respPillar->viewData('artikels')->contains('id', $unlinked->id));

        // Filter supporting
        $respSupporting = $this->actingAs($this->admin)->get(route('admin.artikel.index', ['cluster' => 'supporting']));
        $respSupporting->assertOk();
        $this->assertFalse($respSupporting->viewData('artikels')->contains('id', $pillar->id));
        $this->assertTrue($respSupporting->viewData('artikels')->contains('id', $unlinked->id));
        $this->assertTrue($respSupporting->viewData('artikels')->contains('id', $linked->id));

        // Filter missing backlink
        $respMissing = $this->actingAs($this->admin)->get(route('admin.artikel.index', ['cluster' => 'missing_backlink']));
        $respMissing->assertOk();
        $this->assertTrue($respMissing->viewData('artikels')->contains('id', $unlinked->id));
        $this->assertFalse($respMissing->viewData('artikels')->contains('id', $linked->id));
        $this->assertFalse($respMissing->viewData('artikels')->contains('id', $pillar->id));
    }

    public function test_quick_assign_endpoint_updates_article_role(): void
    {
        $article = $this->createArticle([
            'judul' => 'Artikel Cepat',
            'is_pillar' => false,
            'pillar_id' => null,
        ]);

        $pillar = $this->createArticle([
            'judul' => 'Pilar Induk Cepat',
            'is_pillar' => true,
        ]);

        // Assign as pillar
        $respPillar = $this->actingAs($this->admin)->postJson(route('admin.kluster.quick-assign'), [
            'artikel_id' => $article->id,
            'role' => 'pilar',
        ]);
        $respPillar->assertOk()->assertJson(['success' => true]);
        $article->refresh();
        $this->assertTrue($article->isPillar());
        $this->assertNull($article->pillar_id);

        // Assign as supporting
        $respSupporting = $this->actingAs($this->admin)->postJson(route('admin.kluster.quick-assign'), [
            'artikel_id' => $article->id,
            'role' => 'pendukung',
            'pillar_id' => $pillar->id,
        ]);
        $respSupporting->assertOk()->assertJson(['success' => true]);
        $article->refresh();
        $this->assertFalse($article->isPillar());
        $this->assertEquals($pillar->id, $article->pillar_id);

        // Assign back to standard
        $respStandard = $this->actingAs($this->admin)->postJson(route('admin.kluster.quick-assign'), [
            'artikel_id' => $article->id,
            'role' => 'standar',
        ]);
        $respStandard->assertOk()->assertJson(['success' => true]);
        $article->refresh();
        $this->assertFalse($article->isPillar());
        $this->assertNull($article->pillar_id);
    }
}
