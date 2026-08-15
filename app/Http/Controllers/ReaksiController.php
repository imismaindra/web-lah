<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Reaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReaksiController extends Controller
{
    public function toggle(Request $request, Artikel $artikel): JsonResponse|RedirectResponse
    {
        $tipe = $request->input('tipe', 'suka');
        $userId = $request->user()->id;

        $existing = Reaksi::where('artikel_id', $artikel->id)
            ->where('user_id', $userId)
            ->where('tipe', $tipe)
            ->first();

        if ($existing) {
            $existing->delete();
            $active = false;
        } else {
            Reaksi::create(['artikel_id' => $artikel->id, 'user_id' => $userId, 'tipe' => $tipe]);
            $active = true;
        }

        $count = $artikel->reaksis()->where('tipe', $tipe)->count();

        if ($request->expectsJson()) {
            return response()->json(['active' => $active, 'count' => $count]);
        }

        return back();
    }
}
