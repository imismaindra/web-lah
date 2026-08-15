<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artikel_id')->constrained('artikels')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tipe')->default('suka');
            $table->timestamps();

            $table->unique(['artikel_id', 'user_id', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reaksis');
    }
};
