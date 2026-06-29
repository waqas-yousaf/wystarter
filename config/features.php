<?php

declare(strict_types=1);

return [
    'ecommerce_enabled' => (bool) env('FEATURE_ECOMMERCE', false),
    'email_verification' => (bool) env('FEATURE_EMAIL_VERIFICATION', false),
];
