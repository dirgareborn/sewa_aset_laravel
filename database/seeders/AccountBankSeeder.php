<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountBank;

class AccountBankSeeder extends Seeder
{
    public function run(): void
    {
        // === Contoh QRIS ===
        AccountBank::create([
            'service_id' => 1,
            'type' => 'qris',
            'bank_name' => 'BNI',
            'account_name' => 'UNM Bisnis Center',
            'account_number' => 'QRIS-UNMBC',
            'bank_icon' => 'icons/bni.png',
            'qris_image' => 'qris/sample.png',
            'merchant_name' => 'UNM Bisnis Center',
            'merchant_id' => '1234567890',
            'status' => 1,
        ]);

        // === Contoh Virtual Account ===
        AccountBank::create([
            'service_id' => 1,
            'type' => 'va',
            'bank_name' => 'BRI',
            'account_name' => 'UNM Bisnis Center',
            'account_number' => '12345678901',
            'bank_icon' => 'icons/bri.png',
            'status' => 1,
        ]);
    }
}
