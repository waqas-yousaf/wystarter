<?php

declare(strict_types=1);

test('security headers are present on web responses', function (): void {
    $this->get('/')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
});

test('hsts header is absent on non-secure requests in test environment', function (): void {
    $this->get('/')->assertHeaderMissing('Strict-Transport-Security');
});

test('security headers are present on auth page responses', function (): void {
    $this->get('/login')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});
