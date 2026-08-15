<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(): View
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with('artikel.kategori')
            ->latest()
            ->paginate(9);

        return view('bookmark', compact('bookmarks'));
    }

    public function toggle(Request $request, Artikel $artikel): JsonResponse|RedirectResponse
    {
        $userId = $request->user()->id;

        $existing = Bookmark::where('artikel_id', $artikel->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            Bookmark::create(['artikel_id' => $artikel->id, 'user_id' => $userId]);
            $bookmarked = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['bookmarked' => $bookmarked]);
        }

        return back();
    }
}
