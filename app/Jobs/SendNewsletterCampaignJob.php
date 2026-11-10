<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNewsletterCampaignJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 600;

    public function __construct(
        public int $newsletterId
    ) {}

    public function handle(): void
    {
        $newsletter = Newsletter::find($this->newsletterId);
        if (! $newsletter) {
            return;
        }

        $sentCount = 0;

        Subscriber::chunk(50, function ($subscribers) use ($newsletter, &$sentCount) {
            foreach ($subscribers as $subscriber) {
                try {
                    Mail::to($subscriber->email)->send(
                        new NewsletterMail($newsletter->subject, $newsletter->message, $subscriber)
                    );
                    $sentCount++;
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        });

        $newsletter->update([
            'total_recipients' => $sentCount,
            'sent_at' => now(),
        ]);
    }
}
