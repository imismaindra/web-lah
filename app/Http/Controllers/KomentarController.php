<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    public function store(Request $request, Artikel $artikel): RedirectResponse
    {
        if ($artikel->status !== 'published') {
            abort(404);
        }

        $rules = [
            'isi' => ['required', 'string', 'max:5000'],
            'parent_id' => ['nullable', 'exists:komentars,id'],
        ];

        if (! auth()->check()) {
            $rules['nama'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $artikel->komentars()->create([
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'nama' => $validated['nama'] ?? null,
            'isi' => $validated['isi'],
            'status' => 'published',
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}
