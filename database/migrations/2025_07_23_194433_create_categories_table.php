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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // savings, car, house, etc.
            $table->string('display_name'); // Savings, Car, House, etc.
            $table->string('color', 7)->default('#3B82F6'); // Hex color for UI
            $table->string('icon')->nullable(); // Icon class or name
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Insert default categories
        $this->seedCategories();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }

    /**
     * Seed the categories table with default data.
     */
    private function seedCategories(): void
    {
        $categories = [
            [
                'name' => 'savings',
                'display_name' => 'Savings',
                'color' => '#10B981',
                'icon' => 'pi pi-wallet',
                'description' => 'General savings goals',
                'sort_order' => 1,
            ],
            [
                'name' => 'emergency',
                'display_name' => 'Emergency Fund',
                'color' => '#EF4444',
                'icon' => 'pi pi-shield',
                'description' => 'Emergency fund and safety net',
                'sort_order' => 2,
            ],
            [
                'name' => 'car',
                'display_name' => 'Car',
                'color' => '#3B82F6',
                'icon' => 'pi pi-car',
                'description' => 'Car purchase, maintenance, or upgrades',
                'sort_order' => 3,
            ],
            [
                'name' => 'house',
                'display_name' => 'House',
                'color' => '#8B5CF6',
                'icon' => 'pi pi-home',
                'description' => 'House down payment, renovations, or mortgage',
                'sort_order' => 4,
            ],
            [
                'name' => 'investment',
                'display_name' => 'Investment',
                'color' => '#F59E0B',
                'icon' => 'pi pi-chart-line',
                'description' => 'Investment portfolio and retirement',
                'sort_order' => 5,
            ],
            [
                'name' => 'vacation',
                'display_name' => 'Vacation',
                'color' => '#06B6D4',
                'icon' => 'pi pi-plane',
                'description' => 'Travel and vacation expenses',
                'sort_order' => 6,
            ],
            [
                'name' => 'education',
                'display_name' => 'Education',
                'color' => '#84CC16',
                'icon' => 'pi pi-graduation-cap',
                'description' => 'Education and learning expenses',
                'sort_order' => 7,
            ],
            [
                'name' => 'wedding',
                'display_name' => 'Wedding',
                'color' => '#EC4899',
                'icon' => 'pi pi-heart',
                'description' => 'Wedding and celebration expenses',
                'sort_order' => 8,
            ],
            [
                'name' => 'business',
                'display_name' => 'Business',
                'color' => '#6366F1',
                'icon' => 'pi pi-briefcase',
                'description' => 'Business startup and expenses',
                'sort_order' => 9,
            ],
            [
                'name' => 'other',
                'display_name' => 'Other',
                'color' => '#6B7280',
                'icon' => 'pi pi-ellipsis-h',
                'description' => 'Other financial goals',
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
};
