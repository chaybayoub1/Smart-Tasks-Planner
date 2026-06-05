<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'group_id')) {
                $table->foreignId('group_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('collaboration_groups')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('tasks', 'assigned_to')) {
                $table->foreignId('assigned_to')
                    ->nullable()
                    ->after('group_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'assigned_to')) {
                $table->dropConstrainedForeignId('assigned_to');
            }

            if (Schema::hasColumn('tasks', 'group_id')) {
                $table->dropConstrainedForeignId('group_id');
            }
        });
    }
};
