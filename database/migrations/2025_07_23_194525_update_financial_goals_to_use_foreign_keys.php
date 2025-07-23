<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('financial_goals', function (Blueprint $table) {
            // Add new foreign key columns
            $table->foreignId('currency_id')->after('category')->constrained();
            $table->foreignId('category_id')->after('currency_id')->constrained();
        });

        // Migrate existing data
        $this->migrateExistingData();

        // Drop old columns
        Schema::table('financial_goals', function (Blueprint $table) {
            $table->dropColumn(['currency', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_goals', function (Blueprint $table) {
            // Add back old columns
            $table->string('currency', 3)->after('category_id')->default('USD');
            $table->string('category')->after('currency')->default('savings');
        });

        // Migrate data back
        $this->migrateDataBack();

        // Drop foreign key columns
        Schema::table('financial_goals', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['currency_id', 'category_id']);
        });
    }

    /**
     * Migrate existing string data to foreign key references.
     */
    private function migrateExistingData(): void
    {
        // Get currency mappings
        $currencies = DB::table('currencies')->pluck('id', 'code')->toArray();
        
        // Get category mappings
        $categories = DB::table('categories')->pluck('id', 'name')->toArray();

        // Update all financial goals
        $goals = DB::table('financial_goals')->get();
        
        foreach ($goals as $goal) {
            $currencyId = $currencies[$goal->currency] ?? $currencies['USD'];
            $categoryId = $categories[$goal->category] ?? $categories['savings'];
            
            DB::table('financial_goals')
                ->where('id', $goal->id)
                ->update([
                    'currency_id' => $currencyId,
                    'category_id' => $categoryId,
                ]);
        }
    }

    /**
     * Migrate foreign key data back to strings (for rollback).
     */
    private function migrateDataBack(): void
    {
        $goals = DB::table('financial_goals')
            ->join('currencies', 'financial_goals.currency_id', '=', 'currencies.id')
            ->join('categories', 'financial_goals.category_id', '=', 'categories.id')
            ->select('financial_goals.id', 'currencies.code as currency', 'categories.name as category')
            ->get();

        foreach ($goals as $goal) {
            DB::table('financial_goals')
                ->where('id', $goal->id)
                ->update([
                    'currency' => $goal->currency,
                    'category' => $goal->category,
                ]);
        }
    }
};
