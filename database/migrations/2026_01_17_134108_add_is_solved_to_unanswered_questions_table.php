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
        Schema::table('unanswered_questions', function (Blueprint $table) {
            $table->boolean('is_solved')->default(false)->after('bot_response');
            $table->foreignId('solved_by_intent_id')->nullable()->constrained('intents')->onDelete('set null')->after('is_solved');
            $table->timestamp('solved_at')->nullable()->after('solved_by_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unanswered_questions', function (Blueprint $table) {
            $table->dropForeign(['solved_by_intent_id']);
            $table->dropColumn(['is_solved', 'solved_by_intent_id', 'solved_at']);
        });
    }
};
