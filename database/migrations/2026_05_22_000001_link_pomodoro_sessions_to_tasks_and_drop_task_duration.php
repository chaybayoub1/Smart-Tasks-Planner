<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pomodoro_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('pomodoro_sessions', 'task_id')) {
                $table->foreignId('task_id')
                    ->nullable()
                    ->after('subject_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'duration')) {
                $table->integer('duration')->default(60);
            }
        });

        Schema::table('pomodoro_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('pomodoro_sessions', 'task_id')) {
                $table->dropConstrainedForeignId('task_id');
            }
        });
    }
};
