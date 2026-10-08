<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Store info
            'store_name' => 'ShopPulss',
            'store_tagline' => 'Premium Essentials. Direct to You. Zero Middlemen.',
            'store_email' => 'support@shoppulss.com',
            'store_phone' => '+923328912706',
            'store_address' => 'Karachi, Pakistan',
            'store_currency' => 'PKR',
            'store_timezone' => 'Asia/Karachi',
            'store_logo' => '/images/logo.svg',
            'store_favicon' => '/images/favicon.ico',
            // Order settings
            'free_shipping_minimum' => '2500',
            'cod_available' => 'true',
            'cod_charges' => '50',
            'tax_rate' => '0',
            'order_prefix' => 'ORD',
            // Announcement bar
            'announcement_text' => '🚀 Free delivery over Rs 2,500 &nbsp;&nbsp;|&nbsp;&nbsp; 💰 Cash on Delivery (COD) available nationwide',
            'announcement_enabled' => 'true',
            // Social
            'facebook_url' => 'https://facebook.com/shoppulss',
            'instagram_url' => 'https://instagram.com/shoppulss',
            'whatsapp_number' => '+923328912706',
            'twitter_url' => '',
            // Whatsapp
            'whatsapp_helpline' => '+923328912706',
            // Payment
            'cod_enabled' => 'true',
            'easypaisa_enabled' => 'false',
            'jazzcash_enabled' => 'false',
            // Meta
            'meta_title' => 'ShopPulss - Premium Essentials. Direct to You.',
            'meta_description' => 'Shop authentic products at fair prices. Handpicked from 100+ trusted brands. Shipped directly from our central Karachi fulfilment facility.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
