<?php

return [
    'app' => [
        'name' => env('APP_NAME', 'Fragwatch'),
        'locale' => env('APP_LOCALE', 'ru'),
        'environment' => env('APP_ENV', 'DEVELOPMENT')
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'username' => env('TELEGRAM_BOT_USERNAME'),
        'commands_path' => APP_DIR . '/Bot/Commands'
    ],

    'webhook' => [
        'url' => env('WEBHOOK_URL')
    ],

    'server' => [
        'ip' => env('SERVER_IP', '0.0.0.0'),
        'port' => env('SERVER_PORT', 27015),
        'timeout' => 1,
        'engine' => \xPaw\SourceQuery\SourceQuery::GOLDSOURCE
    ],

    'maps' => [
        'host' => 'https://raw.githubusercontent.com/livegame-esports/cms/refs/heads/master/files/maps_imgs/',
        'fallback' => 'https://raw.githubusercontent.com/livegame-esports/cms/refs/heads/master/files/miniatures/main.jpg'
    ]
];
