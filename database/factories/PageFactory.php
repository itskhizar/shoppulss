<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'category' => 'policy',
            'body' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'summary' => fake()->sentence(10),
            'seo_title' => $title.' | ShopPulss',
            'seo_description' => fake()->sentence(15),
            'canonical_url' => null,
            'robots_directive' => 'index, follow',
            'is_published' => true,
            'show_in_footer' => true,
            'show_in_sitemap' => true,
            'effective_at' => now()->subMonths(1),
        ];
    }
}
