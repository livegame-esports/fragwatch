<?php

namespace App\Bot;

use Config\Config;
use Longman\TelegramBot\Telegram;

try {
    $telegram = new Telegram(Config::TELEGRAM_BOT_TOKEN, Config::TELEGRAM_USERNAME);
    // $telegram->setWebhook(Config::TELEGRAM_WEBHOOK_URL);

    $telegram->enableAdmins([]);
    $telegram->addCommandsPath(Config::TELEGRAM_COMMANDS_PATH);

    $telegram->useGetUpdatesWithoutDatabase();

    echo "Telegram Bot initialized with token: " . Config::TELEGRAM_BOT_TOKEN . "\n";
    while (true) { 
        $telegram->handleGetUpdates(); 
        sleep(1); // Prevents high CPU usage in the loop
    }
} catch (\Longman\TelegramBot\Exception\TelegramException $e) {
    throw new \RuntimeException('Telegram Bot Error: ' . $e->getMessage(), $e->getCode() ?: -1);
}
