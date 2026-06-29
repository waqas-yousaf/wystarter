<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

beforeEach(function (): void {
    config([
        'features.ecommerce_enabled' => false,
        'features.email_verification' => false,
    ]);
});

test('feature config returns false by default', function (): void {
    expect(config('features.ecommerce_enabled'))->toBeFalse();
    expect(config('features.email_verification'))->toBeFalse();
});

test('feature config can be overridden at runtime', function (): void {
    config(['features.ecommerce_enabled' => true]);
    expect(config('features.ecommerce_enabled'))->toBeTrue();
});

test('feature middleware returns 404 when flag is false', function (): void {
    config(['features.ecommerce_enabled' => false]);

    Route::get('/_test_feature', fn () => 'ok')->middleware('feature:ecommerce_enabled');

    $this->get('/_test_feature')->assertNotFound();
});

test('feature middleware passes through when flag is true', function (): void {
    config(['features.ecommerce_enabled' => true]);

    Route::get('/_test_feature_on', fn () => 'ok')->middleware('feature:ecommerce_enabled');

    $this->get('/_test_feature_on')->assertOk()->assertSee('ok');
});

test('feature middleware works for any config key', function (): void {
    config(['features.email_verification' => false]);

    Route::get('/_test_email_feature', fn () => 'ok')->middleware('feature:email_verification');

    $this->get('/_test_email_feature')->assertNotFound();
});
