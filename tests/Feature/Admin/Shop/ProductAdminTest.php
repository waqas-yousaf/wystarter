<?php

declare(strict_types=1);

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\User;

beforeEach(function (): void {
    config(['features.ecommerce_enabled' => true]);
    $this->admin = User::factory()->admin()->create();
    $this->actingAs($this->admin);
});

it('lists products in the admin panel', function (): void {
    $product = Product::factory()->create();

    $this->get(route('admin.shop.products.index'))
        ->assertOk()
        ->assertSee($product->name);
});

it('renders the create product form', function (): void {
    $this->get(route('admin.shop.products.create'))->assertOk();
});

it('stores a new product', function (): void {
    $this->post(route('admin.shop.products.store'), [
        'name' => 'Test Product',
        'price' => '29.99',
        'stock_qty' => '10',
        'status' => ProductStatus::Active->value,
    ])
        ->assertRedirect(route('admin.shop.products.index'));

    $this->assertDatabaseHas('products', ['name' => 'Test Product']);
});

it('renders the edit product form', function (): void {
    $product = Product::factory()->create();

    $this->get(route('admin.shop.products.edit', $product))->assertOk()->assertSee($product->name);
});

it('updates an existing product', function (): void {
    $product = Product::factory()->create();

    $this->put(route('admin.shop.products.update', $product), [
        'name' => 'Updated Name',
        'price' => '49.99',
        'stock_qty' => '5',
        'status' => ProductStatus::Active->value,
    ])
        ->assertRedirect(route('admin.shop.products.index'));

    $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
});

it('deletes a product', function (): void {
    $product = Product::factory()->create();

    $this->delete(route('admin.shop.products.destroy', $product))
        ->assertRedirect(route('admin.shop.products.index'));

    $this->assertModelMissing($product);
});

it('returns 404 when ecommerce feature is disabled', function (): void {
    config(['features.ecommerce_enabled' => false]);

    $this->get(route('admin.shop.products.index'))->assertNotFound();
});
