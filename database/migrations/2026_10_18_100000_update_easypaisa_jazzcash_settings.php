<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'easypaisa_account_number'],
            ['value' => '03328912706', 'updated_at' => now(), 'created_at' => now()]
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'easypaisa_account_title'],
            ['value' => 'Khizar Ahmad', 'updated_at' => now(), 'created_at' => now()]
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'jazzcash_account_number'],
            ['value' => '03328912706', 'updated_at' => now(), 'created_at' => now()]
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'jazzcash_account_title'],
            ['value' => 'Khizar Ahmad', 'updated_at' => now(), 'created_at' => now()]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep active payment settings
    }
};
