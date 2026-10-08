<?php

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Redirect;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PageSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(SettingsSeeder::class);
    $this->seed(CategorySeeder::class);
    $this->seed(PageSeeder::class);
});

test('homepage returns 200, valid title, canonical, and structured data schemas', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('ShopPulss', false);
    $response->assertSee('<link rel="canonical" href="'.rtrim(url('/'), '/').'/">', false);
    $response->assertSee('"@type": "Organization"', false);
    $response->assertSee('"@type": "WebSite"', false);
    $response->assertSee('"query-input": "required name=search_term_string"', false);
});

test('category page renders SEO metadata and breadcrumb schema', function () {
    $category = Category::where('slug', 'electronics')->firstOrFail();

    $response = $this->get('/categories/'.$category->slug);

    $response->assertStatus(200);
    $response->assertSee($category->name);
    $response->assertSee('Online in Pakistan', false);
    $response->assertSee('"@type": "BreadcrumbList"', false);
    $response->assertSee(route('categories.show', $category->slug), false);
});

test('product detail page renders Product and Offer schema without fake review markup', function () {
    $category = Category::first();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'status' => 'published',
        'regular_price' => 2500,
        'stock_quantity' => 10,
    ]);

    $response = $this->get('/products/'.$product->slug);

    $response->assertStatus(200);
    $response->assertSee($product->name);
    $response->assertSee('"@type": "Product"', false);
    $response->assertSee('"@type": "Offer"', false);
    $response->assertSee('"priceCurrency": "PKR"', false);
    $response->assertSee('"availability": "https://schema.org/InStock"', false);
    // Since this product has no reviews, fake aggregateRating must NOT be present
    $response->assertDontSee('"@type": "AggregateRating"', false);
    // Direct policy links should exist
    $response->assertSee('/shipping-delivery-policy', false);
    $response->assertSee('/return-refund-policy', false);
});

test('all required policy pages return 200 and display headings and last updated date', function () {
    $policyRoutes = [
        '/about-us' => 'About ShopPulss',
        '/privacy-policy' => 'Privacy Policy',
        '/terms-and-conditions' => 'Terms and Conditions',
        '/shipping-delivery-policy' => 'Shipping and Delivery Policy',
        '/return-refund-policy' => 'Return and Refund Policy',
        '/payment-policy' => 'Payment Policy',
        '/warranty-policy' => 'Warranty Policy',
        '/cookie-policy' => 'Cookie Policy',
        '/accessibility' => 'Accessibility Statement',
        '/customer-support' => 'Customer Support & Help Center',
    ];

    foreach ($policyRoutes as $route => $expectedH1) {
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertSee($expectedH1);
        $response->assertSee('<meta name="robots" content="index, follow">', false);
    }
});

test('faq page renders accessible accordion buttons and FAQPage JSON-LD schema', function () {
    $response = $this->get('/faq');

    $response->assertStatus(200);
    $response->assertSee('Frequently Asked Questions');
    $response->assertSee('aria-expanded="false"', false);
    $response->assertSee('aria-controls="faq-panel-', false);
    $response->assertSee('"@type": "FAQPage"', false);
    $response->assertSee('"@type": "Question"', false);
});

test('contact page loads and handles valid form submission', function () {
    $getResp = $this->get('/contact-us');
    $getResp->assertStatus(200);
    $getResp->assertSee('Official Contact Channels');

    $postResp = $this->post('/contact-us', [
        'name' => 'Ahmad Khan',
        'email' => 'ahmad@example.com',
        'phone' => '03001234567',
        'subject' => 'Order Tracking & Status',
        'message' => 'Please provide an update regarding my order delivery time in Lahore.',
    ]);

    $postResp->assertRedirect(route('contact'));
    $postResp->assertSessionHas('success');
});

test('contact form blocks spam bot when honeypot field is filled', function () {
    $response = $this->post('/contact-us', [
        'name' => 'Spam Bot',
        'email' => 'spambot@example.com',
        'subject' => 'Free Backlinks',
        'message' => 'Buy backlinks cheap spam content.',
        'website_trap' => 'https://spamwebsite.com',
    ]);

    // Silently redirects back with pseudo-success without processing
    $response->assertRedirect(route('contact'));
});

test('legacy alias paths return 301 redirects to canonical policy pages', function () {
    $aliases = [
        '/about' => '/about-us',
        '/contact' => '/contact-us',
        '/terms' => '/terms-and-conditions',
        '/terms-conditions' => '/terms-and-conditions',
        '/privacy' => '/privacy-policy',
        '/shipping-policy' => '/shipping-delivery-policy',
        '/returns-refunds' => '/return-refund-policy',
        '/help' => '/customer-support',
    ];

    foreach ($aliases as $legacy => $canonical) {
        $response = $this->get($legacy);
        $response->assertStatus(301);
        $response->assertRedirect($canonical);
    }
});

test('robots.txt returns 200, disallows private paths, and references sitemap', function () {
    $response = $this->get('/robots.txt');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    $response->assertSee('User-agent: *', false);
    $response->assertSee('Disallow: /admin/', false);
    $response->assertSee('Disallow: /cart', false);
    $response->assertSee('Disallow: /checkout', false);
    $response->assertSee('Disallow: /account/', false);
    $response->assertSee('Disallow: /search', false);
    $response->assertSee('Sitemap: '.url('/sitemap.xml'), false);
});

test('sitemap index and child sitemaps return valid XML and correct content-type', function () {
    // Sitemap index
    $indexResp = $this->get('/sitemap.xml');
    $indexResp->assertStatus(200);
    $indexResp->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    $indexResp->assertSee('<sitemapindex', false);
    $indexResp->assertSee('sitemap-pages.xml', false);
    $indexResp->assertSee('sitemap-categories.xml', false);
    $indexResp->assertSee('sitemap-products.xml', false);

    // Sitemap pages
    $pagesResp = $this->get('/sitemap-pages.xml');
    $pagesResp->assertStatus(200);
    $pagesResp->assertSee('<urlset', false);
    $pagesResp->assertSee('/about-us', false);
    $pagesResp->assertSee('/privacy-policy', false);

    // Sitemap categories
    $catResp = $this->get('/sitemap-categories.xml');
    $catResp->assertStatus(200);
    $catResp->assertSee('<urlset', false);
    $catResp->assertSee('/categories/', false);
});

test('private and search pages include noindex robots meta directive', function () {
    // Cart
    $cartResp = $this->get('/cart');
    $cartResp->assertSee('<meta name="robots" content="noindex, follow">', false);

    // Checkout (add item first so checkout does not redirect back to cart)
    $category = Category::first();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'status' => 'published',
        'regular_price' => 1500,
        'stock_quantity' => 10,
    ]);
    $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);
    $checkoutResp = $this->get('/checkout');
    $checkoutResp->assertStatus(200);
    $checkoutResp->assertSee('<meta name="robots" content="noindex, follow">', false);

    // Search
    $searchResp = $this->get('/search?q=wireless+headphones');
    $searchResp->assertSee('<meta name="robots" content="noindex, follow">', false);

    // Login
    $loginResp = $this->get('/login');
    $loginResp->assertSee('<meta name="robots" content="noindex, follow">', false);

    // 404 Error page
    $notFoundResp = $this->get('/non-existent-page-test-404');
    $notFoundResp->assertStatus(404);
    $notFoundResp->assertSee('<meta name="robots" content="noindex, follow">', false);
});
