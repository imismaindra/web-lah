<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->foreignId('era_id')
                ->nullable()
                ->after('kategori_id')
                ->constrained('eras')
                ->nullOnDelete();
        });

        Schema::create('artikel_topik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artikel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topik_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['artikel_id', 'topik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel_topik');

        Schema::table('artikels', function (Blueprint $table) {
            $table->dropConstrainedForeignId('era_id');
        });
    }
};
