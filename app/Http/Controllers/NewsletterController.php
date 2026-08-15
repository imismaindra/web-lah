<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmationMail;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = Subscriber::firstOrCreate(['email' => $validated['email']]);

        if ($subscriber->wasRecentlyCreated) {
            try {
                Mail::to($subscriber->email)->send(new SubscriptionConfirmationMail($subscriber));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $message = $subscriber->wasRecentlyCreated
            ? 'Terima kasih! Cek email kamu untuk konfirmasi berlangganan.'
            : 'Email ini sudah terdaftar.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function unsubscribe(string $token): View
    {
        $subscriber = Subscriber::where('token', $token)->first();

        return view('newsletter.unsubscribe', compact('subscriber'));
    }

    public function destroySubscription(string $token): RedirectResponse
    {
        $subscriber = Subscriber::where('token', $token)->first();

        if (! $subscriber) {
            return redirect()->route('newsletter.unsubscribe', $token)
                ->with('status', 'Email ini sudah berhenti berlangganan sebelumnya.');
        }

        $email = $subscriber->email;
        $subscriber->delete();

        return redirect()->route('newsletter.unsubscribe', $token)
            ->with('status', 'Email '.$email.' berhasil berhenti berlangganan.');
    }
}
