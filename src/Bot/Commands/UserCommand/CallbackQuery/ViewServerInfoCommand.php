<?php

namespace App\Bot\Commands\UserCommand\CallbackQuery;

use App\SourceQuery\ServerInfo;
use App\SourceQuery\SourceQuery;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\InlineKeyboard;

class ViewServerInfoCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'view_server_info';

    /**
     * @var string
     */
    protected $description = 'View the server information';

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
                ['text' => '🎮 O‘yinchilarni ko‘rish', 'callback_data' => 'view_players'],
            ]
        );

        $query = SourceQuery::Connect();
        $this->server_info = new ServerInfo($query->GetInfo());
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
        $callback_query = $this->getCallbackQuery();

        $callback_id = $callback_query->getId();
        $chat_id = $callback_query->getMessage()->getChat()->getId();
        $message_id = $callback_query->getMessage()->getMessageId();

        $output = fmtServerInfo($this->server_info);

        Request::editMessageCaption([
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'caption' => $output,
            'parse_mode' => 'HTML',
            'reply_markup' => $this->inline_keyboard
        ]);

        return Request::answerCallbackQuery([
            'callback_query_id' => $callback_id,
        ]);
    }
}
