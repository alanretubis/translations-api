<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key', 255);
            $table->foreignId('locale_id')->constrained('locales')->cascadeOnDelete();
            $table->longText('value');
            $table->json('meta')->nullable(); // optional metadata
            $table->timestamps();

            $table->unique(['locale_id', 'key']);
            $table->index(['locale_id', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
