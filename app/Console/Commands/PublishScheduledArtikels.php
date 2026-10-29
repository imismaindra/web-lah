<?php

namespace App\Console\Commands;

use App\Models\Artikel;
use Illuminate\Console\Command;

class PublishScheduledArtikels extends Command
{
    protected $signature = 'artikel:publish-scheduled';

    protected $description = 'Publikasikan artikel yang sudah mencapai waktu jadwal tayang';

    public function handle(): int
    {
        $count = Artikel::where('status', 'scheduled')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        if ($count > 0) {
            $this->info("Berhasil menerbitkan {$count} artikel terjadwal.");
        } else {
            $this->comment('Tidak ada artikel terjadwal yang perlu diterbitkan.');
        }

        return self::SUCCESS;
    }
}

