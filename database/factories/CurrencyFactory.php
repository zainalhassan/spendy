<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currencies = [
            ['name' => 'Test Dollar', 'code' => 'TST', 'symbol' => 'T$'],
            ['name' => 'Test Euro', 'code' => 'TSE', 'symbol' => 'T€'],
            ['name' => 'Test Pound', 'code' => 'TSP', 'symbol' => 'T£'],
            ['name' => 'Test Yen', 'code' => 'TSY', 'symbol' => 'T¥'],
            ['name' => 'Test Franc', 'code' => 'TSF', 'symbol' => 'TF'],
        ];
        
        $currency = $this->faker->unique()->randomElement($currencies);
        
        return [
            'name' => $currency['name'],
            'code' => $currency['code'],
            'symbol' => $currency['symbol'],
            'position' => $this->faker->randomElement(['before', 'after']),
            'thousand_separator' => $this->faker->randomElement([',', '.', "'"]),
            'decimal_separator' => $this->faker->randomElement(['.', ',']),
            'decimals' => $this->faker->randomElement([0, 2]),
            'is_active' => true,
        ];
    }
}
