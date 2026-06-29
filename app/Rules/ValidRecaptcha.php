<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

final class ValidRecaptcha implements ValidationRule
{
    public function __construct(private readonly float $minimumScore = 0.5) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            $fail('Please complete the reCAPTCHA verification.');

            return;
        }

        $response = Http::timeout(10)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('recaptcha.secret_key'),
            'response' => $value,
        ]);

        $data = $response->json();

        if (! ($data['success'] ?? false)) {
            $fail('reCAPTCHA verification failed. Please try again.');

            return;
        }

        if (isset($data['score']) && $data['score'] < $this->minimumScore) {
            $fail('reCAPTCHA score too low. Please try again.');
        }
    }
}
