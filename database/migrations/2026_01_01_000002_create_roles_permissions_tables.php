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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->primary(['role_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type'], 'idx_model');
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->primary(['permission_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type'], 'idx_permission_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
