<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KomentarController extends Controller
{
    public function index(Request $request): View
    {
        $query = Komentar::with(['artikel', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $komentars = $query->paginate(15)->withQueryString();

        return view('admin.komentar.index', compact('komentars'));
    }

    public function destroy(Komentar $komentar): RedirectResponse
    {
        $komentar->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
