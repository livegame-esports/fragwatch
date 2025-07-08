<?php

namespace App\SourceQuery;

class PlayersInfo
{
    public string $hostname;
    public string $description;

    public string $map;
    public int $players_count = 0;
    public int $max_players = 32;

    /**
     *
     * @param array $server_info
     * @return PlayersInfo
     */
    public function __construct(array $server_info)
    {
        $this->hostname = htmlspecialchars($server_info['HostName']) ?? 'Unknown';
        $this->description = htmlspecialchars($server_info['ModDesc']) ?? 'Unknown';

        $this->map = $server_info['Map'] ?? 'Unknown';
        $this->players_count = $server_info['Players'] ?? 0;
        $this->max_players = $server_info['MaxPlayers'] ?? 0;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }
}
