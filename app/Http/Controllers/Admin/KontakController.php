<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakPesan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KontakController extends Controller
{
    public function index(Request $request): View
    {
        $query = KontakPesan::latest();

        if ($request->status === 'unread') {
            $query->unread();
        } elseif ($request->status === 'read') {
            $query->where('is_read', true);
        }

        $pesans = $query->paginate(15)->withQueryString();
        $unreadCount = KontakPesan::unread()->count();

        return view('admin.kontak.index', compact('pesans', 'unreadCount'));
    }

    public function show(KontakPesan $pesan): View
    {
        if (! $pesan->is_read) {
            $pesan->update(['is_read' => true]);
        }

        return view('admin.kontak.show', compact('pesan'));
    }

    public function toggleRead(KontakPesan $pesan): RedirectResponse
    {
        $pesan->update(['is_read' => ! $pesan->is_read]);

        return back()->with('success', 'Status pesan diperbarui.');
    }

    public function destroy(KontakPesan $pesan): RedirectResponse
    {
        $pesan->delete();

        return redirect()->route('admin.kontak.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
