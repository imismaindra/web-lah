<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Services\ContentClusterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KlusterController extends Controller
{
    public function __construct(
        private ContentClusterService $clusterService
    ) {}

    public function index(Request $request): View
    {
        $audit = $this->clusterService->getClusterAudit();
        $pillars = Artikel::pillars()->orderBy('judul')->get();

        return view('admin.kluster.index', compact('audit', 'pillars'));
    }

    public function graphData(Request $request): JsonResponse
    {
        $pillarId = $request->filled('pillar_id') ? (int) $request->pillar_id : null;
        $onlyIssues = $request->boolean('only_issues');

        $data = $this->clusterService->getGraphData($pillarId, $onlyIssues);

        return response()->json($data);
    }

    public function sync(): RedirectResponse
    {
        $artikels = Artikel::all();
        foreach ($artikels as $artikel) {
            $this->clusterService->syncInternalLinks($artikel);
        }

        return redirect()->route('admin.kluster.index')->with('success', 'Jalur internal linking seluruh artikel berhasil disinkronkan.');
    }

    public function searchLinks(Request $request): JsonResponse
    {
        $query = trim($request->input('q', ''));
        $currentArticleId = $request->filled('current_id') ? (int) $request->current_id : null;
        $pillarId = $request->filled('pillar_id') ? (int) $request->pillar_id : null;

        $articlesQuery = Artikel::query()
            ->with('kategori:id,nama')
            ->when($currentArticleId, fn ($q) => $q->where('id', '!=', $currentArticleId));

        if ($query !== '') {
            $articlesQuery->where(function ($q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%");
            });
        }

        $articles = $articlesQuery
            ->orderByRaw('CASE WHEN is_pillar = 1 THEN 1 ELSE 2 END')
            ->latest('published_at')
            ->limit(20)
            ->get(['id', 'judul', 'slug', 'is_pillar', 'pillar_id', 'kategori_id', 'status']);

        $pillarArticle = null;
        if ($pillarId) {
            $pillarArticle = Artikel::find($pillarId);
        }

        $results = $articles->map(fn (Artikel $a) => [
            'id' => $a->id,
            'title' => $a->judul,
            'slug' => $a->slug,
            'url' => route('artikel.show', $a),
            'is_pillar' => (bool) $a->is_pillar,
            'is_current_pillar' => $pillarId && $a->id === $pillarId,
            'category' => $a->kategori?->nama ?? 'Tanpa Kategori',
            'status' => $a->status,
        ]);

        return response()->json([
            'results' => $results,
            'pillar' => $pillarArticle ? [
                'id' => $pillarArticle->id,
                'title' => $pillarArticle->judul,
                'slug' => $pillarArticle->slug,
                'url' => route('artikel.show', $pillarArticle),
            ] : null,
        ]);
    }

    public function opportunities(Request $request): JsonResponse
    {
        $content = $request->input('content', '');
        $currentArticleId = $request->filled('current_id') ? (int) $request->current_id : null;

        $suggestions = $this->clusterService->findLinkOpportunities($content, $currentArticleId);

        return response()->json([
            'suggestions' => $suggestions,
        ]);
    }
}
