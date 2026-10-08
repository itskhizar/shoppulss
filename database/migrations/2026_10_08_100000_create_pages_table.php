<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category', 50)->default('policy');
            $table->longText('body');
            $table->text('summary')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots_directive', 50)->default('index, follow');
            $table->string('og_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('show_in_footer')->default(true);
            $table->boolean('show_in_sitemap')->default(true);
            $table->timestamp('effective_at')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
