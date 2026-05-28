<?php
// database/migrations/2024_01_01_000009_add_profile_and_collaboration.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Add profile fields to users ──────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('avatar')->nullable()->after('username');
            $table->string('bio')->nullable()->after('avatar');
            $table->string('university')->nullable()->after('bio');
            $table->string('academic_level')->nullable()->after('university');
            $table->string('field_of_study')->nullable()->after('academic_level');
            $table->json('study_methods')->nullable()->after('field_of_study');
            $table->decimal('study_goal', 4, 1)->default(2)->after('study_methods');
            $table->string('theme')->default('dark')->after('study_goal');
            $table->string('timezone')->default('UTC')->after('theme');
            $table->string('language')->default('en')->after('timezone');
        });

        // ── Study Groups ─────────────────────────────────────
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('subject')->nullable();
            $table->string('invite_code', 8)->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_public')->default(false);
            $table->integer('max_members')->default(10);
            $table->timestamps();
        });

        // ── Group Members ─────────────────────────────────────
        Schema::create('study_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['owner', 'admin', 'member'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->unique(['study_group_id', 'user_id']);
        });

        // ── Group Chat Messages ───────────────────────────────
        Schema::create('group_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamps();
        });

        // ── Shared Tasks (group task board) ───────────────────
        Schema::create('group_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_tasks');
        Schema::dropIfExists('group_messages');
        Schema::dropIfExists('study_group_members');
        Schema::dropIfExists('study_groups');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username','avatar','bio','university','academic_level',
                'field_of_study','study_methods','study_goal','theme','timezone','language'
            ]);
        });
    }
};
