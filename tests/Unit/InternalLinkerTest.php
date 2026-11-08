<?php

namespace Tests\Unit;

use App\Models\Artikel;
use App\Services\InternalLinker;
use Tests\TestCase;

class InternalLinkerTest extends TestCase
{
    public function test_autolinker_inserts_link_on_matching_keyword(): void
    {
        $html = '<p>Banyak peristiwa menarik terjadi pada era Kekaisaran Romawi yang patut dipelajari.</p>';
        $currentArticle = new Artikel(['slug' => 'artikel-lain']);

        $result = InternalLinker::link($html, $currentArticle);

        $this->assertStringContainsString('/artikel/mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi', $result);
        $this->assertStringContainsString('>Kekaisaran Romawi</a>', $result);
    }

    public function test_autolinker_does_not_link_within_headings_or_existing_links(): void
    {
        $html = '<h2>Kekaisaran Romawi</h2><p><a href="/sudah-ada">Kekaisaran Romawi</a> sudah terpasang link.</p>';
        $currentArticle = new Artikel(['slug' => 'artikel-lain']);

        $result = InternalLinker::link($html, $currentArticle);

        // H2 should not be wrapped with <a>
        $this->assertStringNotContainsString('<h2><a', $result);
        $this->assertStringNotContainsString('<h2>Kekaisaran <a', $result);
        // Existing <a> should not be double-linked
        $this->assertStringNotContainsString('<a href="/sudah-ada"><a', $result);
    }

    public function test_autolinker_does_not_self_link(): void
    {
        $html = '<p>Artikel ini membahas tentang Kekaisaran Romawi.</p>';
        $currentArticle = new Artikel(['slug' => 'mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi']);

        $result = InternalLinker::link($html, $currentArticle);

        $this->assertSame($html, $result);
    }
}
