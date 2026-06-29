<?php

declare(strict_types=1);

use App\Rules\ValidRecaptcha;
use Illuminate\Support\Facades\Http;

test('rule passes when recaptcha v3 is valid with passing score', function (): void {
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.9,
            'action' => 'submit',
        ]),
    ]);

    $failed = false;
    $rule = new ValidRecaptcha;
    $rule->validate('g-recaptcha-response', 'valid-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('rule passes when recaptcha v2 returns success without a score', function (): void {
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
        ]),
    ]);

    $failed = false;
    $rule = new ValidRecaptcha;
    $rule->validate('g-recaptcha-response', 'valid-v2-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('rule fails when recaptcha response is empty', function (): void {
    $message = null;
    $rule = new ValidRecaptcha;
    $rule->validate('g-recaptcha-response', '', function (string $msg) use (&$message): void {
        $message = $msg;
    });

    expect($message)->toBeString()->not->toBeEmpty();
});

test('rule fails when google returns success false', function (): void {
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => false,
            'error-codes' => ['invalid-input-response'],
        ]),
    ]);

    $message = null;
    $rule = new ValidRecaptcha;
    $rule->validate('g-recaptcha-response', 'bad-token', function (string $msg) use (&$message): void {
        $message = $msg;
    });

    expect($message)->toBeString()->not->toBeEmpty();
});

test('rule fails when v3 score is below minimum threshold', function (): void {
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.2,
        ]),
    ]);

    $message = null;
    $rule = new ValidRecaptcha;
    $rule->validate('g-recaptcha-response', 'bot-token', function (string $msg) use (&$message): void {
        $message = $msg;
    });

    expect($message)->toBeString()->not->toBeEmpty();
});

test('rule passes when v3 score exactly meets the minimum threshold', function (): void {
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.5,
        ]),
    ]);

    $failed = false;
    $rule = new ValidRecaptcha;
    $rule->validate('g-recaptcha-response', 'borderline-token', function () use (&$failed): void {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('rule respects a custom minimum score', function (): void {
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response([
            'success' => true,
            'score' => 0.7,
        ]),
    ]);

    $message = null;
    $rule = new ValidRecaptcha(minimumScore: 0.9);
    $rule->validate('g-recaptcha-response', 'medium-score-token', function (string $msg) use (&$message): void {
        $message = $msg;
    });

    expect($message)->toBeString()->not->toBeEmpty();
});
