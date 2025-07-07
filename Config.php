<?php

namespace Config;

use xPaw\SourceQuery\SourceQuery;

class Config
{
    const TELEGRAM_BOT_TOKEN = "7030649163:AAGq3W-Q2-53xe6OuaPIDan8pXhcPzDxx2Q"; // Replace with your Telegram bot token
    const TELEGRAM_USERNAME = "experimentals_by_tkhrv_bot"; // Replace with your Telegram bot username
    const TELEGRAM_WEBHOOK_URL = ""; // Replace with your Telegram webhook URL

    const SERVER_IP = "46.8.29.170"; // Replace with your server's IP address
    const SERVER_PORT = 27015; // Replace with your server's port

    /**
     * Please!
     * 
     * Do not edit/change these properties.
     */
    const SERVER_TIMEOUT = 1; // seconds
    const SERVER_ENGINE = SourceQuery::GOLDSOURCE;

    const TELEGRAM_COMMANDS_PATH = __DIR__ . '/src/Bot/Commands';
}
