<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // USD, EUR, etc.
            $table->string('name'); // US Dollar, Euro, etc.
            $table->string('symbol'); // $, €, etc.
            $table->enum('position', ['before', 'after'])->default('before');
            $table->string('thousand_separator', 1)->default(',');
            $table->string('decimal_separator', 1)->default('.');
            $table->integer('decimals')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default currencies
        $this->seedCurrencies();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }

    /**
     * Seed the currencies table with default data.
     */
    private function seedCurrencies(): void
    {
        $currencies = [
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'position' => 'before',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'position' => 'before',
                'thousand_separator' => '.',
                'decimal_separator' => ',',
                'decimals' => 2,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'position' => 'before',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            [
                'code' => 'JPY',
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'position' => 'before',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 0,
            ],
            [
                'code' => 'CAD',
                'name' => 'Canadian Dollar',
                'symbol' => 'C$',
                'position' => 'before',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            [
                'code' => 'AUD',
                'name' => 'Australian Dollar',
                'symbol' => 'A$',
                'position' => 'before',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            [
                'code' => 'CHF',
                'name' => 'Swiss Franc',
                'symbol' => 'CHF',
                'position' => 'before',
                'thousand_separator' => "'",
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            [
                'code' => 'CNY',
                'name' => 'Chinese Yuan',
                'symbol' => '¥',
                'position' => 'before',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            [
                'code' => 'INR',
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'position' => 'before',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'decimals' => 2,
            ],
            [
                'code' => 'BRL',
                'name' => 'Brazilian Real',
                'symbol' => 'R$',
                'position' => 'before',
                'thousand_separator' => '.',
                'decimal_separator' => ',',
                'decimals' => 2,
            ],
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->insert($currency);
        }
    }
};
