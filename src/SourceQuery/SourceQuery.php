<?php

namespace App\SourceQuery;

use Config\Config;
use xPaw\SourceQuery\SourceQuery as BaseSourceQuery;
use xPaw\SourceQuery\Exception\{
    SourceQueryException,
    InvalidArgumentException,
    AuthenticationException,
    InvalidPacketException,
    SocketException
};

class SourceQuery
{
    public static function Connect(): BaseSourceQuery
    {
        $sourceQuery = new BaseSourceQuery();

        try {
            $sourceQuery->Connect(Config::SERVER_IP, Config::SERVER_PORT, Config::SERVER_TIMEOUT, Config::SERVER_ENGINE);
        } catch (SourceQueryException | InvalidArgumentException | AuthenticationException | InvalidPacketException | SocketException $e) {
            throw new \RuntimeException("Failed to connect to server: " . $e->getMessage(), $e->getCode() ?: -1);
        }

        return $sourceQuery;
    }
}
