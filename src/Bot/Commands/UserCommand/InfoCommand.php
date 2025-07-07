<?php

namespace App\Bot\Commands\UserCommand;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\InlineKeyboard;

class InfoCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'info';

    /**
     * @var string
     */
    protected $description = 'Get information about the server';

    /**
     * @var string
     */
    protected $usage = '/info';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var 
     */
    protected InlineKeyboard $inline_keyboard;

    /**
     * Pre-execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function preExecute(): ServerResponse
    {
        $this->inline_keyboard = new InlineKeyboard(
            [
                ['text' => '🎮 O‘yinchilarni ko‘rish', 'callback_data' => 'view_players'],
            ]
        );

        return parent::preExecute();
    }

    /**
     * Execute the command.
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();

        $query = \App\SourceQuery\SourceQuery::connect(); {
            $server_info = $query->GetInfo();
            $map = $server_info['Map'] ?? 'Unknown';
        }
        $query->Disconnect();

        $output = fmtServerInfo($server_info);

        return Request::sendPhoto([
            'chat_id' => $chatId,
            'photo' => getImageUrl($map),
            'caption' => $output,
            'parse_mode' => 'HTML',
            'reply_markup' => $this->inline_keyboard
        ]);
    }
}
