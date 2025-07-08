<?php

namespace App\Bot;

use Longman\TelegramBot\Telegram;

$BOT_TOKEN = config('telegram.bot_token');
$BOT_USERNAME = config('telegram.username');

try {
    $telegram = new Telegram($BOT_TOKEN, $BOT_USERNAME);

    $telegram->enableAdmins([]);
    $telegram->addCommandsPath(config('telegram.commands_path'));

    if (config('app.environment') !== 'PRODUCTION') {
        echo "Telegram Bot initialized with token: " . $BOT_TOKEN . "\n";

        $telegram->useGetUpdatesWithoutDatabase();
        while (true) {
            $telegram->handleGetUpdates();
            sleep(1); // Prevents high CPU usage in the loop
        }
    }

    $telegram->handle();
} catch (\Longman\TelegramBot\Exception\TelegramException $e) {
    throw new \RuntimeException('Telegram Bot Error: ' . $e->getMessage(), $e->getCode() ?: -1);
}
