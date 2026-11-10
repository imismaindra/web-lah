<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterCampaignJob;
use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $subscribers = Subscriber::latest()->paginate(20, ['*'], 'subscribers_page');
        $campaigns = Newsletter::with('sender')->latest()->paginate(10, ['*'], 'campaigns_page');
        $totalSubscribers = Subscriber::count();

        return view('admin.newsletter.index', compact('subscribers', 'campaigns', 'totalSubscribers'));
    }

    public function create(): View
    {
        $totalSubscribers = Subscriber::count();

        return view('admin.newsletter.create', compact('totalSubscribers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
        ]);

        $subscriberCount = Subscriber::count();
        if ($subscriberCount === 0) {
            return back()->with('error', 'Belum ada subscriber untuk dikirimi buletin.');
        }

        $newsletter = Newsletter::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'total_recipients' => 0,
        ]);

        SendNewsletterCampaignJob::dispatch($newsletter->id);

        return redirect()->route('admin.newsletter.index')
            ->with('success', "Kampanye buletin sedang dikirimkan ke {$subscriberCount} subscriber via antrean latar belakang.");
    }

    public function testSend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
        ]);

        $admin = auth()->user();
        $dummySubscriber = new Subscriber([
            'email' => $admin->email,
            'token' => 'test-preview-token',
        ]);

        try {
            Mail::to($admin->email)->send(
                new NewsletterMail('[UJI COBA] '.$validated['subject'], $validated['message'], $dummySubscriber)
            );

            return back()->withInput()->with('success', 'Email uji coba berhasil dikirim ke '.$admin->email);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Gagal mengirim email uji coba: '.$e->getMessage());
        }
    }

    public function destroy(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber '.$subscriber->email.' berhasil dihapus.');
    }
}
