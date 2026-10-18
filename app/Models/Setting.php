<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        $value = $setting ? $setting->value : $default;

        // Permanently replace old placeholder phone/whatsapp numbers with actual store number
        if (in_array($key, ['store_phone', 'whatsapp_number', 'whatsapp_helpline'])) {
            if (empty($value) || str_contains((string) $value, '300 000') || str_contains((string) $value, '3000000000') || str_contains((string) $value, '3000000')) {
                return '+923328912706';
            }
        }

        if (in_array($key, ['easypaisa_account_number', 'jazzcash_account_number'])) {
            if (empty($value) || str_contains((string) $value, '0300') || str_contains((string) $value, '0321')) {
                return '03328912706';
            }
        }

        if (in_array($key, ['easypaisa_account_title', 'jazzcash_account_title'])) {
            if (empty($value) || str_contains((string) $value, 'ShopPulss') || str_contains((string) $value, 'Admin')) {
                return 'Khizar Ahmad';
            }
        }

        return $value;
    }

    public static function set(string $key, mixed $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );
    }
}
