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
