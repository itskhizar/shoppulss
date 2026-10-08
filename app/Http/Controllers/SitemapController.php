<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate the primary Sitemap Index at /sitemap.xml
     */
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_index_xml', 3600, function () {
            $lastmod = now()->toAtomString();

            $sitemaps = [
                ['loc' => url('/sitemap-pages.xml'), 'lastmod' => $lastmod],
                ['loc' => url('/sitemap-categories.xml'), 'lastmod' => $lastmod],
                ['loc' => url('/sitemap-products.xml'), 'lastmod' => $lastmod],
            ];

            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $out .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

            foreach ($sitemaps as $sm) {
                $out .= "  <sitemap>\n";
                $out .= "    <loc>{$sm['loc']}</loc>\n";
                $out .= "    <lastmod>{$sm['lastmod']}</lastmod>\n";
                $out .= "  </sitemap>\n";
            }

            $out .= '</sitemapindex>';

            return $out;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Sitemap for static, legal, and editorial pages at /sitemap-pages.xml
     */
    public function pages(): Response
    {
        $xml = Cache::remember('sitemap_pages_xml', 3600, function () {
            $urls = [
                [
                    'loc' => url('/'),
                    'lastmod' => now()->format('Y-m-d'),
                    'changefreq' => 'daily',
                    'priority' => '1.0',
                ],
                [
                    'loc' => route('products.index'),
                    'lastmod' => now()->format('Y-m-d'),
                    'changefreq' => 'daily',
                    'priority' => '0.9',
                ],
                [
                    'loc' => route('about'),
                    'lastmod' => now()->startOfMonth()->format('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                ],
                [
                    'loc' => route('contact'),
                    'lastmod' => now()->startOfMonth()->format('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                ],
                [
                    'loc' => route('faq'),
                    'lastmod' => now()->startOfMonth()->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ],
                [
                    'loc' => route('orders.track'),
                    'lastmod' => now()->startOfMonth()->format('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ],
            ];

            // Published legal & CMS pages
            $pages = Page::published()
                ->where('show_in_sitemap', true)
                ->where('robots_directive', 'like', '%index%')
                ->get();

            foreach ($pages as $p) {
                $urls[] = [
                    'loc' => route('pages.show', $p->slug),
                    'lastmod' => ($p->updated_at ?? now())->format('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ];
            }

            return $this->buildUrlsetXml($urls);
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Sitemap for categories and subcategories at /sitemap-categories.xml
     */
    public function categories(): Response
    {
        $xml = Cache::remember('sitemap_categories_xml', 3600, function () {
            $categories = Category::active()
                ->withCount('products')
                ->get();

            $urls = [];

            foreach ($categories as $cat) {
                // Prioritize top-level categories higher than subcategories
                $isTopLevel = is_null($cat->parent_id);
                $urls[] = [
                    'loc' => route('categories.show', $cat->slug),
                    'lastmod' => ($cat->updated_at ?? now())->format('Y-m-d'),
                    'changefreq' => 'daily',
                    'priority' => $isTopLevel ? '0.85' : '0.75',
                ];
            }

            return $this->buildUrlsetXml($urls);
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Sitemap for canonical indexable products at /sitemap-products.xml
     */
    public function products(): Response
    {
        $xml = Cache::remember('sitemap_products_xml', 1800, function () {
            $products = Product::published()
                ->select(['id', 'slug', 'updated_at', 'is_featured', 'stock_quantity'])
                ->orderByDesc('updated_at')
                ->limit(10000)
                ->get();

            $urls = [];

            foreach ($products as $prod) {
                $urls[] = [
                    'loc' => route('products.show', $prod->slug),
                    'lastmod' => ($prod->updated_at ?? now())->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority' => $prod->is_featured ? '0.9' : '0.7',
                ];
            }

            return $this->buildUrlsetXml($urls);
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    protected function buildUrlsetXml(array $urls): string
    {
        $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $u) {
            $loc = htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8');
            $out .= "  <url>\n";
            $out .= "    <loc>{$loc}</loc>\n";
            if (! empty($u['lastmod'])) {
                $out .= "    <lastmod>{$u['lastmod']}</lastmod>\n";
            }
            if (! empty($u['changefreq'])) {
                $out .= "    <changefreq>{$u['changefreq']}</changefreq>\n";
            }
            if (! empty($u['priority'])) {
                $out .= "    <priority>{$u['priority']}</priority>\n";
            }
            $out .= "  </url>\n";
        }

        $out .= '</urlset>';

        return $out;
    }
}
