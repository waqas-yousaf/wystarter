<?php

declare(strict_types=1);

return [
    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    'minimum_score' => (float) env('RECAPTCHA_MINIMUM_SCORE', '0.5'),
];
