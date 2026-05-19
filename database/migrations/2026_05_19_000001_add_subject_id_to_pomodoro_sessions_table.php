<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Safe to run even if subject_id already exists — checks first.
     */
    public function up(): void
    {
        Schema::table('pomodoro_sessions', function (Blueprint $table) {
            // Only add if the column doesn't exist yet
            if (! Schema::hasColumn('pomodoro_sessions', 'subject_id')) {
                $table->foreignId('subject_id')
                      ->nullable()
                      ->after('user_id')
                      ->constrained('subjects')
                      ->nullOnDelete(); // If subject deleted, session stays but link is cleared
                $table->index('subject_id'); // For fast aggregation queries
            }
        });
    }

    public function down(): void
    {
        Schema::table('pomodoro_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('pomodoro_sessions', 'subject_id')) {
                $table->dropForeign(['subject_id']);
                $table->dropIndex(['subject_id']);
                $table->dropColumn('subject_id');
            }
        });
    }
};
