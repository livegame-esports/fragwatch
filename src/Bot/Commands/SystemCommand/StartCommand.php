<?php

namespace App\Bot\Commands\SystemCommand;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $text = "Welcome to the bot! Use /help to see available commands.";
        return Request::sendMessage(['chat_id' => $this->getMessage()->getChat()->getId(), 'text' => $text]);
    }
}