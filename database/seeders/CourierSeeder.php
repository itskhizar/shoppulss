<?php

namespace Database\Seeders;

use App\Models\Courier;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            [
                'name' => 'Leopards Courier',
                'code' => 'leopards',
                'tracking_url_template' => 'https://www.leopardscourier.com/tracking?track={tracking_number}',
                'contact_phone' => '021-111-300-786',
                'contact_email' => 'customerservice@leopardscourier.com',
                'is_active' => true,
                'api_settings' => [
                    'api_key' => env('LEOPARDS_API_KEY', ''),
                    'api_password' => env('LEOPARDS_API_PASSWORD', ''),
                    'sandbox' => env('LEOPARDS_SANDBOX', true),
                    'pickup_address' => 'ShopPulss Central Fulfillment Hub, Main Expressway, Abbottabad / Karachi',
                ],
            ],
            [
                'name' => 'TCS Express',
                'code' => 'tcs',
                'tracking_url_template' => 'https://www.tcsexpress.com/tracking?track={tracking_number}',
                'contact_phone' => '021-111-123-456',
                'contact_email' => 'customercare@tcsexpress.com',
                'is_active' => true,
                'api_settings' => [
                    'client_id' => env('TCS_CLIENT_ID', ''),
                    'client_secret' => env('TCS_CLIENT_SECRET', ''),
                    'cost_center_code' => env('TCS_COST_CENTER', ''),
                    'sandbox' => env('TCS_SANDBOX', true),
                ],
            ],
            [
                'name' => 'Trax Logistics',
                'code' => 'trax',
                'tracking_url_template' => 'https://trax.pk/tracking?tracking_number={tracking_number}',
                'contact_phone' => '021-111-118-729',
                'contact_email' => 'info@trax.pk',
                'is_active' => true,
                'api_settings' => [
                    'api_key' => env('TRAX_API_KEY', ''),
                    'sandbox' => env('TRAX_SANDBOX', true),
                ],
            ],
            [
                'name' => 'M&P Express Logistics',
                'code' => 'mnp',
                'tracking_url_template' => 'https://mulphilog.com/tracking?cn={tracking_number}',
                'contact_phone' => '021-111-202-020',
                'contact_email' => 'contact@mulphilog.com',
                'is_active' => true,
                'api_settings' => [],
            ],
            [
                'name' => 'ShopPulss Express (Direct Rider)',
                'code' => 'custom',
                'tracking_url_template' => null,
                'contact_phone' => '+92 300 0000000',
                'contact_email' => 'support@shoppulss.com',
                'is_active' => true,
                'api_settings' => [],
            ],
        ];

        foreach ($couriers as $courier) {
            Courier::updateOrCreate(
                ['code' => $courier['code']],
                $courier
            );
        }

        // Add payment and bank settings
        $paymentSettings = [
            'bank_transfer_enabled' => 'true',
            'bank_name' => 'Meezan Bank Limited',
            'bank_account_title' => 'ShopPulss Private Limited',
            'bank_account_number' => '01020304050607',
            'bank_iban' => 'PK78MEZN0001020304050607',
            'bank_branch' => 'Main Commercial Branch',
            'bank_instructions' => 'Please transfer the total amount via Mobile Banking / ATM. Enter your Transaction Reference ID below for immediate manual verification.',
            'easypaisa_enabled' => 'true',
            'easypaisa_account_title' => 'ShopPulss Store',
            'easypaisa_account_number' => '03001234567',
            'jazzcash_enabled' => 'true',
            'jazzcash_account_title' => 'ShopPulss Store',
            'jazzcash_account_number' => '03007654321',
        ];

        foreach ($paymentSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
