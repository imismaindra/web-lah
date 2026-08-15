<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $subscribers = Subscriber::latest()->get();

        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function create(): View
    {
        return view('admin.newsletter.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
        ]);

        $subscribers = Subscriber::all();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'Belum ada subscriber untuk dikirimi buletin.');
        }

        $sent = 0;
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($validated['subject'], $validated['message'], $subscriber));
            $sent++;
        }

        return redirect()->route('admin.newsletter.index')
            ->with('success', "Buletin terkirim ke {$sent} subscriber.");
    }

    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber '.$subscriber->email.' berhasil dihapus.');
    }
}
