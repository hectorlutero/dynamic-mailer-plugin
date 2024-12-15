<?php
return [
    'host' => $_ENV['MAIL_HOST'],
    'port' => $_ENV['MAIL_PORT'],
    'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
    'username' => $_ENV['MAIL_USERNAME'],
    'password' => $_ENV['MAIL_PASSWORD'],
    'from' => [
        'address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@example.com',
        'name' => $_ENV['MAIL_FROM_NAME'] ?? 'DynamicMailer',
    ],
    'templates' => [
        'theme_override_path' => $_ENV['MAIL_THEME_PATH'] ?? null
    ]
];
