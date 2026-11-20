<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->boolean('is_pillar')->default(false)->index()->after('views');
            $table->foreignId('pillar_id')->nullable()->after('is_pillar')->constrained('artikels')->nullOnDelete();
        });

        Schema::create('artikel_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_artikel_id')->constrained('artikels')->cascadeOnDelete();
            $table->foreignId('target_artikel_id')->constrained('artikels')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['source_artikel_id', 'target_artikel_id']);
            $table->index('target_artikel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel_links');

        Schema::table('artikels', function (Blueprint $table) {
            $table->dropForeign(['pillar_id']);
            $table->dropColumn(['is_pillar', 'pillar_id']);
        });
    }
};
