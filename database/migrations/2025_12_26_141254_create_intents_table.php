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
        Schema::create('intents', function (Blueprint $table) {
            $table->id();
            $table->string('dialogflow_id')->nullable()->unique();
            $table->string('display_name')->unique();
            $table->text('description')->nullable();
            $table->integer('priority')->default(500000);
            $table->boolean('is_fallback')->default(false);
            $table->json('training_phrases')->nullable();
            $table->json('responses')->nullable();
            $table->json('events')->nullable();
            $table->json('input_contexts')->nullable();
            $table->json('output_contexts')->nullable();
            $table->boolean('webhook_enabled')->default(false);
            $table->string('action')->nullable();
            $table->boolean('synced')->default(false);
            $table->timestamp('last_synced_at')->nullable();
            $table->integer('usage_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intents');
    }
};
