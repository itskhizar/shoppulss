<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(['key' => 'store_phone'], ['value' => '+923328912706', 'updated_at' => now()]);
        DB::table('settings')->updateOrInsert(['key' => 'whatsapp_number'], ['value' => '+923328912706', 'updated_at' => now()]);
        DB::table('settings')->updateOrInsert(['key' => 'whatsapp_helpline'], ['value' => '+923328912706', 'updated_at' => now()]);
    }

    public function down(): void {}
};
