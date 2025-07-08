<?php

namespace App\Bot\Commands\UserCommand;

use App\SourceQuery\ServerInfo;
use App\SourceQuery\SourceQuery;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\InlineKeyboard;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;

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
     * @var InlineKeyboard
     */
    protected InlineKeyboard $inline_keyboard;

    /**
     * @var ServerInfo
     */
    protected ServerInfo $server_info;

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
                ['text' => __('view_players'), 'callback_data' => 'view_players'],
            ]
        );

        $query = SourceQuery::Connect();
        try {
            $this->server_info = new ServerInfo($query->GetInfo());
        } catch (InvalidPacketException|SocketException $e) {
            \Longman\TelegramBot\Request::sendMessage(['chat_id' => '1243167621', 'text' => config('server.ip')]);
        }
        $query->Disconnect();

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
        $output = fmtServerInfo($this->server_info);

        return Request::sendPhoto([
            'chat_id' => $chatId,
            'photo' => getImageUrl($this->server_info->map),
            'caption' => $output,
            'parse_mode' => 'HTML',
            'reply_markup' => $this->inline_keyboard
        ]);
    }
}
