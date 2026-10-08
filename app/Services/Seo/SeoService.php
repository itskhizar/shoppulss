<?php

namespace App\Services\Seo;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class SeoService
{
    protected string $title = '';

    protected string $description = '';

    protected ?string $canonical = null;

    protected string $robots = 'index, follow';

    protected string $ogType = 'website';

    protected ?string $ogImage = null;

    protected array $breadcrumbs = [];

    protected array $schemas = [];

    protected bool $includeBrandInTitle = true;

    public function __construct()
    {
        $this->title = config('seo.default_title', 'ShopPulss | Shop Quality Products Online in Pakistan');
        $this->description = config('seo.default_description', 'Shop products across popular categories at ShopPulss. Explore electronics, mobile phones, electrical items and more with clear product details and customer support in Pakistan.');
        $this->ogImage = asset('images/shoppulss-logo.png');
    }

    public function setTitle(?string $title, bool $includeBrand = true): self
    {
        if ($title) {
            $this->title = trim($title);
            $this->includeBrandInTitle = $includeBrand;
        }

        return $this;
    }

    public function setDescription(?string $description): self
    {
        if ($description) {
            $clean = strip_tags($description);
            $this->description = Str::limit(trim(preg_replace('/\s+/', ' ', $clean)), 165);
        }

        return $this;
    }

    public function setCanonical(?string $url): self
    {
        $this->canonical = $url ? $this->cleanCanonicalUrl($url) : null;

        return $this;
    }

    public function setRobots(string $directive): self
    {
        $this->robots = $directive;

        return $this;
    }

    public function setOgType(string $type): self
    {
        $this->ogType = $type;

        return $this;
    }

    public function setOgImage(?string $url): self
    {
        if ($url) {
            $this->ogImage = Str::startsWith($url, 'http') ? $url : url($url);
        }

        return $this;
    }

    public function addBreadcrumb(string $name, ?string $url = null): self
    {
        $this->breadcrumbs[] = [
            'name' => $name,
            'url' => $url ? $this->cleanCanonicalUrl($url) : null,
        ];

        return $this;
    }

    public function addSchema(array $schema): self
    {
        $this->schemas[] = $schema;

        return $this;
    }

    public function getTitle(): string
    {
        $brand = config('seo.site_name', 'ShopPulss');

        if (! $this->includeBrandInTitle || Str::contains($this->title, $brand)) {
            return $this->title;
        }

        $sep = config('seo.title_separator', '|');

        return "{$this->title} {$sep} {$brand}";
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCanonical(): string
    {
        return $this->canonical ?? $this->cleanCanonicalUrl(Request::fullUrl());
    }

    public function getRobots(): string
    {
        return $this->robots;
    }

    public function getOgType(): string
    {
        return $this->ogType;
    }

    public function getOgImage(): string
    {
        return $this->ogImage ?? asset('images/shoppulss-logo.png');
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    public function getSchemas(): array
    {
        return $this->schemas;
    }

    /**
     * Clean and normalize a canonical URL:
     * - Strips ad tracking / analytics query parameters (utm_*, gclid, fbclid, etc.)
     * - Preserves paginated page query parameter ?page=X on listing pages
     * - Discards filter/sort query parameters unless custom
     */
    public function cleanCanonicalUrl(?string $url = null): string
    {
        $target = $url ?? Request::fullUrl();
        $parsed = parse_url($target);

        if (! isset($parsed['scheme']) || ! isset($parsed['host'])) {
            $base = url($target);
            $parsed = parse_url($base);
        }

        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? parse_url(config('app.url', 'https://shoppulss.com'), PHP_URL_HOST);
        $port = isset($parsed['port']) && ! in_array($parsed['port'], [80, 443]) ? ':'.$parsed['port'] : '';
        $path = $parsed['path'] ?? '/';

        // Keep page query parameter if present and > 1
        $queryArgs = [];
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $rawArgs);
            if (isset($rawArgs['page']) && is_numeric($rawArgs['page']) && (int) $rawArgs['page'] > 1) {
                $queryArgs['page'] = (int) $rawArgs['page'];
            }
        }

        $queryString = ! empty($queryArgs) ? '?'.http_build_query($queryArgs) : '';

        return "{$scheme}://{$host}{$port}{$path}{$queryString}";
    }

    /**
     * Setup SEO for Homepage
     */
    public function forHome(): self
    {
        $this->setTitle('ShopPulss | Shop Quality Products Online in Pakistan', false);
        $this->setDescription(Setting::get('meta_description', 'Shop products across popular categories at ShopPulss. Explore electronics, mobile phones, electrical items and more with clear product details and customer support in Pakistan.'));
        $this->setCanonical(url('/'));
        $this->setRobots('index, follow');
        $this->setOgType('website');

        // Site-wide Organization schema
        $orgSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'ShopPulss',
            'url' => url('/'),
            'logo' => asset('images/shoppulss-logo.png'),
            'description' => 'Multi-category direct retail ecommerce store with central fulfillment in Pakistan.',
        ];

        $supportPhone = Setting::get('store_phone', '+923328912706');
        $supportEmail = Setting::get('store_email', 'support@shoppulss.com');

        $orgSchema['contactPoint'] = [
            '@type' => 'ContactPoint',
            'telephone' => $supportPhone,
            'contactType' => 'customer service',
            'email' => $supportEmail,
            'areaServed' => 'PK',
            'availableLanguage' => ['English', 'Urdu'],
        ];

        $socials = array_values(array_filter([
            Setting::get('facebook_url', 'https://facebook.com/shoppulss'),
            Setting::get('instagram_url', 'https://instagram.com/shoppulss'),
            Setting::get('twitter_url'),
        ]));

        if (! empty($socials)) {
            $orgSchema['sameAs'] = $socials;
        }

        $this->addSchema($orgSchema);

        // WebSite schema with public search endpoint
        $this->addSchema([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'ShopPulss',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/search').'?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ]);

        return $this;
    }

    /**
     * Setup SEO for Category and Subcategory pages
     */
    public function forCategory(Category $category, ?int $page = null): self
    {
        $isSubcategory = (bool) $category->parent_id;

        $title = $category->seo_title ?: (
            $isSubcategory
                ? "Buy {$category->name} Online in Pakistan"
                : "{$category->name} Online in Pakistan"
        );

        $description = $category->seo_description ?: (
            $isSubcategory
                ? "Explore {$category->name} online in Pakistan at ShopPulss. Compare available options, prices and key features before you buy."
                : "Shop {$category->name} online in Pakistan at ShopPulss. Browse genuine products with clear pricing, specifications, and nationwide Cash on Delivery."
        );

        $this->setTitle($title);
        $this->setDescription($description);
        $this->setCanonical(route('categories.show', $category->slug));

        if ($category->image_url) {
            $this->setOgImage($category->image_url);
        }

        // Set robots
        $this->setRobots($category->is_active ? 'index, follow' : 'noindex, follow');

        // Breadcrumbs
        $this->addBreadcrumb('Home', route('home'));
        $this->addBreadcrumb('Shop', route('products.index'));

        if ($category->parent) {
            $this->addBreadcrumb($category->parent->name, route('categories.show', $category->parent->slug));
        }

        $this->addBreadcrumb($category->name, route('categories.show', $category->slug));

        // BreadcrumbList JSON-LD
        $this->addSchema($this->buildBreadcrumbSchema());

        return $this;
    }

    /**
     * Setup SEO for Product pages
     */
    public function forProduct(Product $product): self
    {
        $title = $product->seo_title ?: "Buy {$product->name} Online in Pakistan";
        $description = $product->seo_description ?: (
            $product->short_description
                ?: "Buy {$product->name} online in Pakistan at ShopPulss. View price, key specifications, availability, delivery and warranty details."
        );

        $this->setTitle($title);
        $this->setDescription($description);
        $this->setCanonical($product->canonical_url ?: route('products.show', $product->slug));
        $this->setOgType('product');

        if ($product->primary_image_url) {
            $this->setOgImage($product->primary_image_url);
        }

        // Robots
        $isIndexable = ($product->status === 'published');
        $this->setRobots($isIndexable ? 'index, follow' : 'noindex, follow');

        // Breadcrumbs
        $this->addBreadcrumb('Home', route('home'));
        $this->addBreadcrumb('Shop', route('products.index'));

        if ($product->category) {
            if ($product->category->parent) {
                $this->addBreadcrumb($product->category->parent->name, route('categories.show', $product->category->parent->slug));
            }
            $this->addBreadcrumb($product->category->name, route('categories.show', $product->category->slug));
        }

        $this->addBreadcrumb($product->name, route('products.show', $product->slug));
        $this->addSchema($this->buildBreadcrumbSchema());

        // Product Schema
        $productUrl = route('products.show', $product->slug);
        $effectivePrice = (float) $product->effective_price;
        $inStock = $product->hasStock();

        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'url' => $productUrl,
            'description' => Str::limit(strip_tags($product->description ?? $product->short_description ?? $product->name), 400),
            'sku' => $product->sku ?? ('SKU-'.$product->id),
            'offers' => [
                '@type' => 'Offer',
                'url' => $productUrl,
                'priceCurrency' => 'PKR',
                'price' => number_format($effectivePrice, 2, '.', ''),
                'priceValidUntil' => now()->addMonths(3)->format('Y-m-d'),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'ShopPulss',
                ],
            ],
        ];

        if ($product->primary_image_url) {
            $productSchema['image'] = [url($product->primary_image_url)];
        }

        // Only include genuine approved reviews if they exist in DB
        $approvedReviews = $product->relationLoaded('approvedReviews')
            ? $product->approvedReviews
            : $product->approvedReviews()->get();

        if ($approvedReviews->isNotEmpty()) {
            $avgRating = round($approvedReviews->avg('rating'), 1);
            $reviewCount = $approvedReviews->count();

            $productSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $avgRating,
                'reviewCount' => $reviewCount,
                'bestRating' => '5',
                'worstRating' => '1',
            ];

            $reviewsData = [];
            foreach ($approvedReviews->take(5) as $rev) {
                $reviewsData[] = [
                    '@type' => 'Review',
                    'author' => [
                        '@type' => 'Person',
                        'name' => $rev->customer_name ?: 'Verified Customer',
                    ],
                    'reviewRating' => [
                        '@type' => 'Rating',
                        'ratingValue' => $rev->rating,
                        'bestRating' => '5',
                        'worstRating' => '1',
                    ],
                    'reviewBody' => $rev->comment ?? '',
                    'datePublished' => $rev->created_at->format('Y-m-d'),
                ];
            }
            $productSchema['review'] = $reviewsData;
        }

        $this->addSchema($productSchema);

        return $this;
    }

    /**
     * Setup SEO for CMS / Legal pages
     */
    public function forPage(Page $page): self
    {
        $this->setTitle($page->seo_title ?: $page->title);
        $this->setDescription($page->seo_description ?: ($page->summary ?: Str::limit(strip_tags($page->body), 160)));
        $this->setCanonical($page->canonical_url ?: route('pages.show', $page->slug));
        $this->setRobots($page->robots_directive ?? 'index, follow');

        if ($page->og_image) {
            $this->setOgImage($page->og_image);
        }

        $this->addBreadcrumb('Home', route('home'));
        $this->addBreadcrumb($page->title, route('pages.show', $page->slug));
        $this->addSchema($this->buildBreadcrumbSchema());

        return $this;
    }

    /**
     * Setup SEO for Internal Search
     */
    public function forSearch(?string $query = null): self
    {
        $qText = $query ? ': '.e($query) : '';
        $this->setTitle("Search Results{$qText}");
        $this->setDescription('Find genuine products across electronics, gadgets, and lifestyle categories on ShopPulss Pakistan.');
        $this->setCanonical(route('search'));
        $this->setRobots('noindex, follow');

        $this->addBreadcrumb('Home', route('home'));
        $this->addBreadcrumb('Search Results', route('search'));

        return $this;
    }

    /**
     * Setup SEO for FAQ page
     */
    public function forFaq(array $faqs): self
    {
        $this->setTitle('Frequently Asked Questions (FAQ) | Customer Support | ShopPulss');
        $this->setDescription('Find immediate answers to questions about ordering, Cash on Delivery, shipment tracking, 7-day returns, and warranties on ShopPulss Pakistan.');
        $this->setCanonical(route('faq'));
        $this->setRobots('index, follow');

        $this->addBreadcrumb('Home', route('home'));
        $this->addBreadcrumb('FAQ', route('faq'));
        $this->addSchema($this->buildBreadcrumbSchema());

        // Visible FAQPage schema
        if (! empty($faqs)) {
            $entities = [];
            foreach ($faqs as $item) {
                if (! empty($item['q']) && ! empty($item['a'])) {
                    $entities[] = [
                        '@type' => 'Question',
                        'name' => strip_tags($item['q']),
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => strip_tags($item['a']),
                        ],
                    ];
                }
            }

            if (! empty($entities)) {
                $this->addSchema([
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $entities,
                ]);
            }
        }

        return $this;
    }

    /**
     * Setup SEO for private/utility pages (Cart, Checkout, Account, 404)
     */
    public function forNoindex(string $title, ?string $description = null): self
    {
        $this->setTitle($title);
        if ($description) {
            $this->setDescription($description);
        }
        $this->setRobots('noindex, follow');
        $this->setCanonical($this->cleanCanonicalUrl(Request::url()));

        return $this;
    }

    protected function buildBreadcrumbSchema(): array
    {
        $items = [];
        $pos = 1;

        foreach ($this->breadcrumbs as $crumb) {
            $element = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => $crumb['name'],
            ];

            if ($crumb['url']) {
                $element['item'] = $crumb['url'];
            }

            $items[] = $element;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
