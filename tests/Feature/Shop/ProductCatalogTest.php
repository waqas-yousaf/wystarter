<?php

declare(strict_types=1);

use App\Models\Product;

describe('Shop product catalog', function (): void {
    describe('feature flag disabled', function (): void {
        beforeEach(function (): void {
            config(['features.ecommerce_enabled' => false]);
        });

        it('returns 404 on shop index when ecommerce is disabled', function (): void {
            $this->get('/shop')->assertNotFound();
        });

        it('returns 404 on product show when ecommerce is disabled', function (): void {
            $product = Product::factory()->create();

            $this->get("/shop/{$product->slug}")->assertNotFound();
        });
    });

    describe('feature flag enabled', function (): void {
        beforeEach(function (): void {
            config(['features.ecommerce_enabled' => true]);
        });

        it('shows active products on shop index', function (): void {
            $active = Product::factory()->create(['name' => 'Visible Product']);
            Product::factory()->draft()->create(['name' => 'Hidden Draft']);

            $this->get('/shop')
                ->assertOk()
                ->assertSee('Visible Product')
                ->assertDontSee('Hidden Draft');
        });

        it('shows a single active product', function (): void {
            $product = Product::factory()->create();

            $this->get("/shop/{$product->slug}")->assertOk()->assertSee($product->name);
        });
    });
});
