<?php

/**
 * Check if an image is available at the given URL.
 * 
 * @param string $url The URL of the image.
 * @return bool True if the image is available, false otherwise.
 */
function isImageAvailable(string $url): bool
{
    $headers = @get_headers($url);
    if ($headers && strpos($headers[0], '200') !== false) {
        return true;
    }
    return false;
}

/**
 * Get the image URL for a given map.
 * If the image is not available, return a fallback image URL.
 * 
 * @param string $map The name of the map.
 * @return string The URL of the image.
 */
function getImageUrl(string $map): string
{
    $imageUrl = "https://raw.githubusercontent.com/livegame-esports/cms/refs/heads/master/files/maps_imgs/$map.jpg"; // Replace with an actual image URL

    if (!isImageAvailable($imageUrl)) {
        $imageUrl = "https://raw.githubusercontent.com/livegame-esports/cms/refs/heads/master/files/miniatures/main.jpg"; // Fallback image URL
    }

    return $imageUrl;
}

/**
 * Generate a formatted message with server information.
 *
 * @param array $server_info An associative array containing server information.
 *              Expected keys: 'HostName', 'ModDesc', 'Map', 'Players', 'MaxPlayers'.
 * @return string The formatted message.
 */
function fmtServerInfo(array $server_info): string
{
    $hostname = htmlspecialchars($server_info['HostName']) ?? 'Unknown';
    $description = htmlspecialchars($server_info['ModDesc']) ?? 'Unknown';

    $map = $server_info['Map'] ?? 'Unknown';
    $players = $server_info['Players'] ?? 0;
    $maxPlayers = $server_info['MaxPlayers'] ?? 0;

    $output = <<<SERVER_INFO
        <b>$hostname</b>
        <i>$description</i>

        Map: $map
        Players: $players/$maxPlayers
    SERVER_INFO;

    return preg_replace('/^[ \t]+/m', '', $output);
}

/**
 * Format a list of players into a string.
 *
 * @param array $players An array of players, where each player is an associative array with 'Name' and 'Id'.
 * @return string The formatted player list.
 */
function fmtPlayerList(array $players): string
{
    if (empty($players)) {
        return "Serverda o'yinchilar yo'q.";
    }

    // Sort players by 'Frags' in descending order
    usort($players, fn($a, $b) => $b['Frags'] <=> $a['Frags']);

    $medals = ['🥇', '🥈', '🥉'];
    $result = "🎮 <b>Hozirgi o'yinchilar (" . count($players) . ")</b>\n\n";

    foreach ($players as $index => $player) {
        // Assign a medal based on the index
        $icon = $medals[$index] ?? "👤";

        $name = htmlspecialchars($player['Name']);
        $frags = $player['Frags'];
        $time = $player['TimeF'];

        $result .= "$icon <b>$name</b>\n";
        $result .= "Kills: <code>$frags</code>  |  ⏱ <code>$time</code>\n\n";
    }

    return trim($result);
}
