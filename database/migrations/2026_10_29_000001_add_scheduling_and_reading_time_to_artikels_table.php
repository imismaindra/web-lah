<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->string('status', 20)->default('draft')->change();
            $table->timestamp('published_at')->nullable()->after('status');
            $table->unsignedInteger('reading_time')->default(1)->after('konten');
        });

        DB::table('artikels')
            ->where('status', 'published')
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);

        $artikels = DB::table('artikels')->select('id', 'konten')->get();
        foreach ($artikels as $artikel) {
            $words = str_word_count(trim(strip_tags($artikel->konten ?? '')));
            $readingTime = max(1, (int) ceil($words / 200));
            DB::table('artikels')->where('id', $artikel->id)->update(['reading_time' => $readingTime]);
        }
    }

    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn(['published_at', 'reading_time']);
        });
    }
};
