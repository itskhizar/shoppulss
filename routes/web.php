<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Services\Seo\SeoService;
use Illuminate\Support\Facades\Route;

// --- Public Storefront Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product/{slug}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/category/{slug}', [CategoryController::class, 'show']);
Route::get('/search', [ProductController::class, 'index'])->name('search');

// --- Legal, Trust & Informational Routes ---
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact-us', [ContactController::class, 'show'])->name('contact');
Route::post('/contact-us', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/customer-support', [PageController::class, 'support'])->name('support');

// Canonical Policy Routes
Route::get('/privacy-policy', fn (SeoService $seo) => app(PageController::class)->show('privacy-policy', $seo))->name('policy.privacy');
Route::get('/terms-and-conditions', fn (SeoService $seo) => app(PageController::class)->show('terms-and-conditions', $seo))->name('policy.terms');
Route::get('/shipping-delivery-policy', fn (SeoService $seo) => app(PageController::class)->show('shipping-delivery-policy', $seo))->name('policy.shipping');
Route::get('/return-refund-policy', fn (SeoService $seo) => app(PageController::class)->show('return-refund-policy', $seo))->name('policy.returns');
Route::get('/payment-policy', fn (SeoService $seo) => app(PageController::class)->show('payment-policy', $seo))->name('policy.payment');
Route::get('/warranty-policy', fn (SeoService $seo) => app(PageController::class)->show('warranty-policy', $seo))->name('policy.warranty');
Route::get('/cookie-policy', fn (SeoService $seo) => app(PageController::class)->show('cookie-policy', $seo))->name('policy.cookies');
Route::get('/accessibility', fn (SeoService $seo) => app(PageController::class)->show('accessibility', $seo))->name('policy.accessibility');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

// --- XML Sitemaps ---
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-products.xml', [SitemapController::class, 'products'])->name('sitemap.products');

// --- Robots.txt Dynamic Endpoint ---
Route::get('/robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    $content = "User-agent: *\nAllow: /\nAllow: /images/\nAllow: /build/\n\n# Non-public / Private Areas\nDisallow: /admin/\nDisallow: /cart\nDisallow: /cart/\nDisallow: /checkout\nDisallow: /checkout/\nDisallow: /account/\nDisallow: /search\nDisallow: /api/\nDisallow: /login\nDisallow: /register\nDisallow: /forgot-password\nDisallow: /reset-password/\n\nSitemap: {$sitemapUrl}\n";

    return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
});

// Track Order
Route::get('/track-order', [OrderController::class, 'track'])->name('orders.track');

// --- Cart Routes ---
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/item/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/item/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// --- Checkout & Order Confirmation ---
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

// --- Authentication Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// --- Customer Account (Protected) ---
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [AccountController::class, 'orderDetail'])->name('orders.show');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');
});

// --- Admin Panel (Protected: auth + admin) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [Admin\DashboardController::class, 'index']);

    // Products & dynamic category attributes (Catalog Manager & above)
    Route::middleware('role:Store Admin,Catalog Manager')->group(function () {
        Route::get('/products/category-attributes/{categoryId}', [Admin\ProductController::class, 'categoryAttributes'])->name('products.category-attributes');
        Route::resource('products', Admin\ProductController::class);
        Route::resource('categories', Admin\CategoryController::class);
    });

    // Orders, Shipments & Delivery, Payments (Order Manager, Support Agent & above)
    Route::middleware('role:Store Admin,Order Manager,Support Agent')->group(function () {
        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{id}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

        // Delivery & Shipments Management
        Route::post('/orders/{id}/shipment', [Admin\ShipmentController::class, 'store'])->name('orders.shipment.store');
        Route::get('/shipments', [Admin\ShipmentController::class, 'index'])->name('shipments.index');
        Route::patch('/shipments/{id}/status', [Admin\ShipmentController::class, 'updateStatus'])->name('shipments.status');
        Route::get('/shipments/{id}/label', [Admin\ShipmentController::class, 'label'])->name('shipments.label');

        // Payments & Verification Management
        Route::get('/payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments', [Admin\PaymentController::class, 'store'])->name('payments.store');
        Route::post('/payments/{id}/verify', [Admin\PaymentController::class, 'verify'])->name('payments.verify');
        Route::post('/payments/{id}/reject', [Admin\PaymentController::class, 'reject'])->name('payments.reject');
        Route::post('/payments/{id}/cod', [Admin\PaymentController::class, 'recordCod'])->name('payments.cod');
    });

    // Customers (Order Manager, Support Agent & above)
    Route::middleware('role:Store Admin,Order Manager,Support Agent')->group(function () {
        Route::get('/customers', [Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [Admin\CustomerController::class, 'show'])->name('customers.show');
    });

    // Staff & Roles (Super Admin & Store Admin only)
    Route::middleware('role:Store Admin')->group(function () {
        Route::resource('staff', Admin\StaffController::class);
    });
});
