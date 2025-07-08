<?php

namespace App\SourceQuery;

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
            $sourceQuery->Connect(config('server.ip'), config('server.port'), config('server.timeout'), config('server.engine'));
        } catch (SourceQueryException | InvalidArgumentException | AuthenticationException | InvalidPacketException | SocketException $e) {
            throw new \RuntimeException("Failed to connect to server: " . $e->getMessage(), $e->getCode() ?: -1);
        }

        return $sourceQuery;
    }
}
