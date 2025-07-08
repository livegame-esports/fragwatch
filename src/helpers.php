<?php

use App\SourceQuery\PlayersInfo;

/**
 * Check if an image is available at the given URL by sending a HEAD request.
 *
 * @param string $url The URL of the image to check.
 * @return bool True if the image exists and is accessible (HTTP 200), false otherwise.
 */
function isImageAvailable(string $url): bool
{
    $headers = @get_headers($url);
    return $headers && str_contains($headers[0], '200');
}

/**
 * Resolve the image URL for a given map name.
 * Falls back to a default image if the map image is not found.
 *
 * @param string $map The map name (e.g., "de_dust2").
 * @return string The final resolved image URL.
 */
function getImageUrl(string $map): string
{
    $imageUrl = config('maps.host') . "$map.jpg";

    if (!isImageAvailable($imageUrl)) {
        return config('maps.fallback');
    }

    return $imageUrl;
}

/**
 * Generate a formatted Telegram message for server information.
 *
 * @param PlayersInfo $server_info Object containing hostname, description, map, and player count.
 * @return string A formatted HTML message string for Telegram.
 */
function fmtServerInfo(PlayersInfo $server_info): string
{
    $ip = config('server.ip');
    $port = config('server.port');

    $output = "<b>$server_info->hostname</b>\n";
    $output .= "<i>$server_info->description</i>\n\n";

    $output .= __('ip') . ": $ip:$port\n";
    $output .= __('map') . ": $server_info->map\n";
    $output .= __('players') . ": $server_info->players_count/$server_info->max_players\n";

    return $output;
}

/**
 * Render a formatted list of players currently in the server.
 *
 * @param array<int, array{
 *     Id: int,
 *     Name: string,
 *     Frags: int,
 *     Time: int,
 *     TimeF: string
 * }> $players The player data list.
 *
 * @return string A formatted HTML string ready to be sent via Telegram.
 */
function fmtPlayerList(array $players): string
{
    if (empty($players)) {
        return __('no_players');
    }

    usort($players, fn($a, $b) => $b['Frags'] <=> $a['Frags']);

    $medals = ['🥇', '🥈', '🥉'];
    $result = sprintf(__('current_players'), count($players)) . "\n\n";

    foreach ($players as $index => $player) {
        $icon = $medals[$index] ?? "👤";

        $name = htmlspecialchars($player['Name']);
        $frags = $player['Frags'];
        $time = $player['TimeF'];

        $result .= "$icon <b>$name</b>\n";
        $result .= sprintf("%s: <code>%d</code>  |  %s: <code>%s</code>\n\n",
            __('kills'), $frags, __('time'), $time
        );
    }

    return trim($result);
}

/**
 * @param string $key
 * @param string|null $locale
 * @return string
 */
function __(string $key, ?string $locale = null): string
{
    static $translations = [];

    $locale = $locale ?? config('app.locale', 'ru');

    if (!isset($translations[$locale])) {
        $langFile = ROOT_DIR . "/lang/{$locale}.php";
        if (!file_exists($langFile)) return $key;

        $translations[$locale] = require $langFile;
    }

    return $translations[$locale][$key] ?? $key;
}


/**
 * Retrieve a configuration value using dot notation.
 *
 * @param string $key The configuration key (e.g., "telegram.bot_token").
 * @param mixed|null $default A fallback value if the key does not exist.
 * @return mixed The config value or default if not found.
 */
function config(string $key, mixed $default = null): mixed
{
    $config = require ROOT_DIR . '/config.php';

    foreach (explode('.', $key) as $segment) {
        if (!is_array($config) && !array_key_exists($segment, $config)) {
            return $default;
        }
        $config = $config[$segment];
    }

    return $config;
}

/**
 * Load an environment variable from the .env file (if present).
 *
 * @param string $key The environment variable name.
 * @param mixed|null $default The fallback value if not set.
 * @return mixed The environment value or default if not found.
 */
function env(string $key, mixed $default = null): mixed
{
    static $loaded = false;

    if (!$loaded && file_exists(ROOT_DIR . '/.env')) {
        $lines = file(ROOT_DIR . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;

            [$envKey, $value] = array_map('trim', explode('=', $line, 2));
            $value = trim($value, "\"'");

            $_ENV[$envKey] = $value;
        }

        $loaded = true;
    }

    return $_ENV[$key] ?? $default;
}
