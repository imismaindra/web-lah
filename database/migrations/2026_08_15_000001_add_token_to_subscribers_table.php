<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('token', 64)->nullable()->unique()->after('email');
        });

        foreach (DB::table('subscribers')->whereNull('token')->get() as $subscriber) {
            DB::table('subscribers')
                ->where('id', $subscriber->id)
                ->update(['token' => Str::random(32)]);
        }
    }

    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
