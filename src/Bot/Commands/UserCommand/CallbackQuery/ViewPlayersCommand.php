<?php

namespace App\Bot\Commands\UserCommand\CallbackQuery;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\InlineKeyboard;

class ViewPlayersCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'view_players';

    /**
     * @var string
     */
    protected $description = 'View the list of players on the server';

    /**
     * @var InlineKeyboard
     */
    protected InlineKeyboard $inline_keyboard;

    /**
     * Pre-execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     * @throws TelegramException
     */
    public function preExecute(): ServerResponse
    {
        $this->inline_keyboard = new InlineKeyboard(
            [
                ['text' => __('update'), 'callback_data' => 'view_players'],
            ],
            [
                ['text' => __('back'), 'callback_data' => 'view_server_info'],
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
        $callback_query = $this->getCallbackQuery();

        $callback_id = $callback_query->getId();
        $chat_id = $callback_query->getMessage()->getChat()->getId();
        $message_id = $callback_query->getMessage()->getMessageId();

        $query = \App\SourceQuery\SourceQuery::connect(); {
            $players_list = $query->GetPlayers();
            $map = $query->GetInfo()['Map'];
        }
        $query->Disconnect();

        $output = fmtPlayerList($players_list);

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
