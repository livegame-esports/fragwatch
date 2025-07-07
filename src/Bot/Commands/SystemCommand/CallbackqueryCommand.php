<?php

namespace App\Bot\Commands\SystemCommand;

use App\Bot\Commands\UserCommand\CallbackQuery\ViewPlayersCommand;
use App\Bot\Commands\UserCommand\CallbackQuery\ViewServerInfoCommand;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Handle callback queries';

    /**
     * Execute the command.
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        $callback_query = $this->getCallbackQuery();

        switch ($callback_query->getData()) {
            case 'view_players':
                $command = new ViewPlayersCommand($this->getTelegram(), $this->getUpdate());
                $command->preExecute();
                return $command->execute();

            case 'view_server_info':
                $command = new ViewServerInfoCommand($this->getTelegram(), $this->getUpdate());
                $command->preExecute();
                return $command->execute();

            default:
                return Request::emptyResponse();
        }
    }
}
