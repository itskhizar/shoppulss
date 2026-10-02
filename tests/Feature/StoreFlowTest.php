<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page returns successful response with products and categories', function () {
    $category = Category::create([
        'name' => 'Smartphones',
        'slug' => 'smartphones',
        'status' => 'active',
        'is_featured' => true,
        'display_order' => 1,
    ]);

    $product = Product::create([
        'name' => 'Flagship Phone',
        'slug' => 'flagship-phone',
        'sku' => 'FP-001',
        'category_id' => $category->id,
        'regular_price' => 120000,
        'sale_price' => 110000,
        'stock_quantity' => 15,
        'status' => 'published',
        'type' => 'simple',
        'is_featured' => true,
        'is_new' => true,
    ]);

    $product->images()->create([
        'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600',
        'alt_text' => 'Flagship Phone',
        'is_featured' => true,
        'display_order' => 1,
    ]);

    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Flagship Phone');
    $response->assertSee('Smartphones');
});

test('catalog page displays products and filters', function () {
    $response = $this->get('/shop');
    $response->assertStatus(200);
});

test('guest can register account', function () {
    $response = $this->post('/register', [
        'name' => 'Test Customer',
        'email' => 'test_'.uniqid().'@example.com',
        'phone' => '+923001234567',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticated();
});

test('admin can log in and access admin dashboard', function () {
    $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
    $admin = User::firstOrCreate(
        ['email' => 'admin_test@shoppulss.com'],
        ['name' => 'Admin Tester', 'password' => bcrypt('password'), 'status' => 'active']
    );
    $admin->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);

    $loginResponse = $this->post('/login', [
        'email' => 'admin_test@shoppulss.com',
        'password' => 'password',
    ]);

    $loginResponse->assertRedirect(route('admin.dashboard'));

    $dashboardResponse = $this->actingAs($admin)->get('/admin');
    $dashboardResponse->assertStatus(200);
});

test('guest user is blocked from admin dashboard', function () {
    $response = $this->get('/admin');
    $response->assertRedirect(route('login'));
});

test('user can add item to cart and proceed to checkout', function () {
    $product = Product::published()->first();
    if (! $product) {
        $category = Category::firstOrCreate(['name' => 'Test', 'slug' => 'test-cat']);
        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-prod-'.uniqid(),
            'sku' => 'TEST-'.uniqid(),
            'category_id' => $category->id,
            'regular_price' => 1000,
            'stock_quantity' => 10,
            'status' => 'published',
        ]);
    }

    $addResponse = $this->post('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $addResponse->assertSessionHas('success');

    $cartResponse = $this->get('/cart');
    $cartResponse->assertStatus(200);
    $cartResponse->assertSee($product->name);

    $checkoutResponse = $this->get('/checkout');
    $checkoutResponse->assertStatus(200);

    // Process checkout
    $orderResponse = $this->post('/checkout', [
        'full_name' => 'Ali Customer',
        'email' => 'ali@example.com',
        'phone' => '03001234567',
        'province' => 'Punjab',
        'city' => 'Lahore',
        'street_address' => 'House 1, Street 2',
        'payment_method' => 'cod',
    ]);

    $orderResponse->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'email' => 'ali@example.com',
        'phone' => '03001234567',
        'status' => 'pending',
    ]);
});

test('order tracking finds existing order', function () {
    $category = Category::firstOrCreate(['name' => 'Test Cat', 'slug' => 'test-cat-track']);
    $product = Product::create([
        'name' => 'Track Item',
        'slug' => 'track-item-'.uniqid(),
        'sku' => 'TRK-'.uniqid(),
        'category_id' => $category->id,
        'regular_price' => 500,
        'stock_quantity' => 10,
        'status' => 'published',
    ]);

    $order = Order::create([
        'order_number' => 'SP-20260927-TEST',
        'email' => 'customer@shoppulss.com',
        'phone' => '03009999999',
        'status' => 'pending',
        'payment_status' => 'pending',
        'subtotal' => 500,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'shipping_amount' => 199,
        'total_amount' => 699,
        'currency' => 'PKR',
    ]);

    $response = $this->get('/track-order?order_number=SP-20260927-TEST');
    $response->assertStatus(200);
    $response->assertSee('SP-20260927-TEST');
    $response->assertSee('Pending');
});

test('admin can update order status', function () {
    $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
    $admin = User::firstOrCreate(
        ['email' => 'admin_order@shoppulss.com'],
        ['name' => 'Order Admin', 'password' => bcrypt('password'), 'status' => 'active']
    );
    $admin->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);

    $order = Order::create([
        'order_number' => 'SP-STATUS-1234',
        'email' => 'status@shoppulss.com',
        'phone' => '03008888888',
        'status' => 'pending',
        'payment_status' => 'pending',
        'subtotal' => 1000,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'shipping_amount' => 0,
        'total_amount' => 1000,
        'currency' => 'PKR',
    ]);

    $response = $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", [
        'status' => 'confirmed',
        'reason' => 'Order confirmed with customer',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'confirmed',
    ]);
});

test('authenticated customer can access account dashboard and update profile', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'customer_'.uniqid().'@shoppulss.com',
        'phone' => '03001112223',
    ]);

    $response = $this->actingAs($user)->get(route('account.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('Original Name');

    $updateResponse = $this->actingAs($user)->put(route('account.profile.update'), [
        'name' => 'Updated Name',
        'email' => $user->email,
        'phone' => '03009998877',
    ]);

    $updateResponse->assertSessionHas('success');
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'phone' => '03009998877',
    ]);
});

test('regular customer cannot access admin dashboard', function () {
    $customer = User::factory()->create();

    $response = $this->actingAs($customer)->get('/admin');
    $response->assertStatus(403);
});

test('product detail page displays product and variants', function () {
    $category = Category::firstOrCreate(['name' => 'Gadgets', 'slug' => 'gadgets']);
    $product = Product::create([
        'name' => 'Smart Watch Pro',
        'slug' => 'smart-watch-pro-'.uniqid(),
        'sku' => 'SWP-'.uniqid(),
        'category_id' => $category->id,
        'regular_price' => 15000,
        'sale_price' => 12999,
        'stock_quantity' => 20,
        'status' => 'published',
        'type' => 'simple',
        'short_description' => 'A wonderful smart watch.',
    ]);

    $response = $this->get('/products/'.$product->slug);
    $response->assertStatus(200);
    $response->assertSee('Smart Watch Pro');
    $response->assertSee('12,999');
});

test('cart supports updating item quantity and removing item', function () {
    $category = Category::firstOrCreate(['name' => 'Accessories', 'slug' => 'accessories']);
    $product = Product::create([
        'name' => 'Wireless Mouse',
        'slug' => 'wireless-mouse-'.uniqid(),
        'sku' => 'WM-'.uniqid(),
        'category_id' => $category->id,
        'regular_price' => 2500,
        'stock_quantity' => 15,
        'status' => 'published',
        'type' => 'simple',
    ]);

    // Add to cart
    $this->post('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $cartResponse = $this->get('/cart');
    $cartResponse->assertStatus(200);
    $cartResponse->assertSee('Wireless Mouse');

    // Clear cart
    $clearResponse = $this->post('/cart/clear');
    $clearResponse->assertSessionHas('success');

    $emptyCartResponse = $this->get('/cart');
    $emptyCartResponse->assertSee('Your cart is currently empty');
});

test('admin can manage products in catalog', function () {
    $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
    $admin = User::firstOrCreate(
        ['email' => 'admin_catalog@shoppulss.com'],
        ['name' => 'Catalog Admin', 'password' => bcrypt('password'), 'status' => 'active']
    );
    $admin->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);

    $category = Category::firstOrCreate(['name' => 'Laptops', 'slug' => 'laptops']);

    // Admin creates product
    $sku = 'LAP-'.uniqid();
    $createResponse = $this->actingAs($admin)->post('/admin/products', [
        'name' => 'Ultra Slim Laptop',
        'sku' => $sku,
        'category_id' => $category->id,
        'type' => 'simple',
        'regular_price' => 85000,
        'stock_quantity' => 10,
        'status' => 'published',
    ]);

    $createResponse->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'sku' => $sku,
        'name' => 'Ultra Slim Laptop',
    ]);

    $product = Product::where('sku', $sku)->first();

    // Admin updates product
    $updateResponse = $this->actingAs($admin)->put("/admin/products/{$product->id}", [
        'name' => 'Ultra Slim Laptop V2',
        'sku' => $sku,
        'category_id' => $category->id,
        'type' => 'simple',
        'regular_price' => 90000,
        'stock_quantity' => 8,
        'status' => 'published',
    ]);

    $updateResponse->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Ultra Slim Laptop V2',
        'regular_price' => 90000,
    ]);

    // Admin views product list
    $listResponse = $this->actingAs($admin)->get('/admin/products');
    $listResponse->assertStatus(200);
    $listResponse->assertSee('Ultra Slim Laptop V2');
});

test('admin can manage categories', function () {
    $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
    $admin = User::firstOrCreate(
        ['email' => 'admin_cat@shoppulss.com'],
        ['name' => 'Cat Admin', 'password' => bcrypt('password'), 'status' => 'active']
    );
    $admin->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);

    $createResponse = $this->actingAs($admin)->post('/admin/categories', [
        'name' => 'Gaming Consoles',
        'description' => 'All modern consoles and accessories',
        'is_active' => 1,
    ]);

    $createResponse->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', [
        'name' => 'Gaming Consoles',
    ]);
});

test('admin can view customers list and staff list', function () {
    $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
    $admin = User::firstOrCreate(
        ['email' => 'admin_staff@shoppulss.com'],
        ['name' => 'Staff Admin', 'password' => bcrypt('password'), 'status' => 'active']
    );
    $admin->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);

    $customersResponse = $this->actingAs($admin)->get('/admin/customers');
    $customersResponse->assertStatus(200);

    $staffResponse = $this->actingAs($admin)->get('/admin/staff');
    $staffResponse->assertStatus(200);
});

test('admin can view order details and audit trail renders properly', function () {
    $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
    $admin = User::firstOrCreate(
        ['email' => 'admin_ordershow@shoppulss.com'],
        ['name' => 'Show Admin', 'password' => bcrypt('password'), 'status' => 'active']
    );
    $admin->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);

    $order = Order::create([
        'order_number' => 'SP-SHOW-1234',
        'email' => 'show@shoppulss.com',
        'phone' => '03007777777',
        'status' => 'pending',
        'payment_status' => 'pending',
        'subtotal' => 1500,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'shipping_amount' => 100,
        'total_amount' => 1600,
        'currency' => 'PKR',
    ]);

    $order->statusHistories()->create([
        'from_status' => 'pending',
        'to_status' => 'confirmed',
        'reason' => 'Customer confirmed over phone',
        'changed_by' => $admin->id,
        'created_at' => now(),
    ]);

    $response = $this->actingAs($admin)->get("/admin/orders/{$order->id}");
    $response->assertStatus(200);
    $response->assertSee('SP-SHOW-1234');
    $response->assertSee('Customer confirmed over phone');
});

test('role based access control enforces area permissions', function () {
    $catalogRole = Role::firstOrCreate(['name' => 'Catalog Manager'], ['display_name' => 'Catalog Manager']);
    $catalogUser = User::factory()->create();
    $catalogUser->roles()->syncWithoutDetaching([$catalogRole->id => ['model_type' => User::class]]);

    // Catalog manager can view products
    $this->actingAs($catalogUser)->get('/admin/products')->assertStatus(200);

    // Catalog manager CANNOT access orders or staff
    $this->actingAs($catalogUser)->get('/admin/orders')->assertStatus(403);
    $this->actingAs($catalogUser)->get('/admin/staff')->assertStatus(403);

    $orderRole = Role::firstOrCreate(['name' => 'Order Manager'], ['display_name' => 'Order Manager']);
    $orderUser = User::factory()->create();
    $orderUser->roles()->syncWithoutDetaching([$orderRole->id => ['model_type' => User::class]]);

    // Order manager can view orders
    $this->actingAs($orderUser)->get('/admin/orders')->assertStatus(200);

    // Order manager CANNOT access products or staff
    $this->actingAs($orderUser)->get('/admin/products')->assertStatus(403);
    $this->actingAs($orderUser)->get('/admin/staff')->assertStatus(403);
});

test('safe product deletion archives product instead of hard deleting when orders exist', function () {
    $role = Role::firstOrCreate(['name' => 'Super Admin'], ['display_name' => 'Super Admin']);
    $admin = User::factory()->create();
    $admin->roles()->syncWithoutDetaching([$role->id => ['model_type' => User::class]]);

    $category = Category::firstOrCreate(['name' => 'Tech', 'slug' => 'tech-safe']);
    $product = Product::create([
        'name' => 'Historic Gadget',
        'slug' => 'historic-gadget-'.uniqid(),
        'sku' => 'HIST-'.uniqid(),
        'category_id' => $category->id,
        'regular_price' => 2000,
        'stock_quantity' => 5,
        'status' => 'published',
        'type' => 'simple',
    ]);

    $order = Order::create([
        'order_number' => 'SP-HIST-123',
        'email' => 'hist@example.com',
        'phone' => '03001234567',
        'status' => 'delivered',
        'payment_status' => 'paid',
        'subtotal' => 2000,
        'discount_amount' => 0,
        'tax_amount' => 0,
        'shipping_amount' => 0,
        'total_amount' => 2000,
        'currency' => 'PKR',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_sku' => $product->sku,
        'product_name' => $product->name,
        'quantity' => 1,
        'unit_price' => 2000,
        'total_price' => 2000,
    ]);

    $deleteResponse = $this->actingAs($admin)->delete("/admin/products/{$product->id}");
    $deleteResponse->assertRedirect(route('admin.products.index'));

    // Verify it was soft-deleted and marked as archived, not permanently wiped
    $this->assertSoftDeleted('products', ['id' => $product->id]);
    $this->assertDatabaseHas('products', ['id' => $product->id, 'status' => 'archived']);
});
