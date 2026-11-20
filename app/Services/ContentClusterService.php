<?php

namespace App\Services;

use App\Models\Artikel;

class ContentClusterService
{
    /**
     * @return array<int>
     */
    public function extractTargetArticleIds(string $html, ?int $sourceArticleId = null): array
    {
        if (trim($html) === '') {
            return [];
        }

        preg_match_all('/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);
        $hrefs = $matches[1] ?? [];
        if (empty($hrefs)) {
            return [];
        }

        $slugs = [];
        foreach ($hrefs as $href) {
            $path = parse_url($href, PHP_URL_PATH) ?? $href;
            $path = trim($path, '/');

            if (preg_match('#(?:^|/)artikel/([a-z0-9-]+)$#i', $path, $slugMatch)) {
                $slugs[] = $slugMatch[1];
            }
        }

        $slugs = array_values(array_unique(array_filter($slugs)));
        if (empty($slugs)) {
            return [];
        }

        $query = Artikel::whereIn('slug', $slugs);
        if ($sourceArticleId) {
            $query->where('id', '!=', $sourceArticleId);
        }

        return $query->pluck('id')->all();
    }

    public function syncInternalLinks(Artikel $artikel): void
    {
        $targetIds = $this->extractTargetArticleIds($artikel->konten ?? '', $artikel->id);
        $artikel->outgoingLinks()->sync($targetIds);
    }

    public function hasPillarBacklink(Artikel $supportingArticle): bool
    {
        if (! $supportingArticle->pillar_id) {
            return true;
        }

        if ($supportingArticle->relationLoaded('outgoingLinks')) {
            if ($supportingArticle->outgoingLinks->contains('id', $supportingArticle->pillar_id)) {
                return true;
            }
        }

        if ($supportingArticle->pillar) {
            $pillarSlug = $supportingArticle->pillar->slug;
        } else {
            $pillarSlug = Artikel::where('id', $supportingArticle->pillar_id)->value('slug');
        }

        if (! $pillarSlug) {
            return false;
        }

        $konten = $supportingArticle->konten ?? '';

        return (bool) preg_match('#href=["\'][^"\']*artikel/'.preg_quote($pillarSlug, '#').'["\']#i', $konten);
    }

    public function getClusterAudit(): array
    {
        $pillars = Artikel::query()
            ->where('is_pillar', true)
            ->withCount('supportingArticles')
            ->orderBy('judul')
            ->get();

        $supportingArticles = Artikel::query()
            ->whereNotNull('pillar_id')
            ->with(['pillar:id,judul,slug,status', 'outgoingLinks:id,judul,slug'])
            ->orderBy('judul')
            ->get();

        $missingBacklinks = $supportingArticles->filter(fn (Artikel $article) => ! $this->hasPillarBacklink($article))->values();
        $healthyCount = $supportingArticles->count() - $missingBacklinks->count();

        return [
            'total_pillars' => $pillars->count(),
            'total_supporting' => $supportingArticles->count(),
            'missing_backlink_count' => $missingBacklinks->count(),
            'healthy_count' => $healthyCount,
            'missing_backlinks' => $missingBacklinks,
            'pillars' => $pillars,
        ];
    }

    public function getGraphData(?int $filterPillarId = null, bool $onlyIssues = false): array
    {
        $articlesQuery = Artikel::query()->with([
            'kategori:id,nama',
            'pillar:id,judul,slug',
            'outgoingLinks:id,judul,slug',
        ]);

        if ($filterPillarId) {
            $articlesQuery->where(function ($q) use ($filterPillarId) {
                $q->where('id', $filterPillarId)
                    ->orWhere('pillar_id', $filterPillarId);
            });
        }

        $allArticles = $articlesQuery->get();
        $articleMap = $allArticles->keyBy('id');

        $nodes = [];
        $edges = [];
        $edgeKeys = [];

        foreach ($allArticles as $art) {
            $hasBacklink = $art->pillar_id ? $this->hasPillarBacklink($art) : true;

            if ($onlyIssues && $art->pillar_id && $hasBacklink) {
                continue;
            }

            $type = $art->is_pillar ? 'pilar' : ($art->pillar_id ? 'pendukung' : 'standar');

            $nodes[] = [
                'id' => $art->id,
                'title' => $art->judul,
                'slug' => $art->slug,
                'type' => $type,
                'is_pillar' => (bool) $art->is_pillar,
                'pillar_id' => $art->pillar_id,
                'pillar_title' => $art->pillar?->judul,
                'status' => $art->status,
                'views' => $art->views,
                'category' => $art->kategori?->nama ?? 'Tanpa Kategori',
                'has_pillar_backlink' => $hasBacklink,
                'url' => route('artikel.show', $art),
                'edit_url' => route('admin.artikel.edit', $art),
                'outgoing_count' => $art->outgoingLinks->count(),
            ];

            if ($art->pillar_id && isset($articleMap[$art->pillar_id])) {
                $edgeKey = 'cluster_'.$art->id.'_'.$art->pillar_id;
                if (! isset($edgeKeys[$edgeKey])) {
                    $edges[] = [
                        'source' => $art->id,
                        'target' => $art->pillar_id,
                        'type' => 'pillar_link',
                        'is_linked' => $hasBacklink,
                    ];
                    $edgeKeys[$edgeKey] = true;
                }
            }

            foreach ($art->outgoingLinks as $target) {
                if (! isset($articleMap[$target->id])) {
                    continue;
                }

                $edgeKey = 'link_'.$art->id.'_'.$target->id;
                if (! isset($edgeKeys[$edgeKey])) {
                    $edges[] = [
                        'source' => $art->id,
                        'target' => $target->id,
                        'type' => 'internal_link',
                        'is_linked' => true,
                    ];
                    $edgeKeys[$edgeKey] = true;
                }
            }
        }

        return [
            'nodes' => array_values($nodes),
            'edges' => array_values($edges),
        ];
    }

    public function findLinkOpportunities(string $content, ?int $currentArticleId = null): array
    {
        $plainText = strip_tags($content);
        if (trim($plainText) === '') {
            return [];
        }

        $candidates = Artikel::published()
            ->when($currentArticleId, fn ($q) => $q->where('id', '!=', $currentArticleId))
            ->select(['id', 'judul', 'slug', 'is_pillar'])
            ->limit(50)
            ->get();

        $opportunities = [];
        $existingLinkedIds = $this->extractTargetArticleIds($content, $currentArticleId);
        $linkedIdMap = array_fill_keys($existingLinkedIds, true);

        foreach ($candidates as $cand) {
            if (isset($linkedIdMap[$cand->id])) {
                continue;
            }

            if (mb_stripos($plainText, $cand->judul) !== false) {
                $opportunities[] = [
                    'id' => $cand->id,
                    'title' => $cand->judul,
                    'slug' => $cand->slug,
                    'keyword' => $cand->judul,
                    'is_pillar' => (bool) $cand->is_pillar,
                    'url' => route('artikel.show', $cand),
                ];
                if (count($opportunities) >= 8) {
                    break;
                }
            }
        }

        return $opportunities;
    }
}
