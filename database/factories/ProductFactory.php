<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    protected $model = Product::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 500),
            'compare_price' => null,
            'sku' => mb_strtoupper(fake()->unique()->bothify('??-####')),
            'stock_qty' => fake()->numberBetween(0, 100),
            'status' => ProductStatus::Active,
            'featured_image' => null,
            'category_id' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => ProductStatus::Draft]);
    }

    public function inactive(): static
    {
        return $this->state(['status' => ProductStatus::Inactive]);
    }
}
