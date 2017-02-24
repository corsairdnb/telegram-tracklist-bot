#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Longman\TelegramBot\TelegramLog as Log;

$config = require 'config.php';

define('API_KEY', $config['API_KEY']);
$BOT_NAME = $config['BOT_NAME'];
$mysql_credentials = [
    'host'     => $config['MYSQL_HOST'],
    'user'     => $config['MYSQL_USER'],
    'password' => $config['MYSQL_PASS'],
    'database' => $config['MYSQL_DB'],
];
$ADMIN_ID = $config['ADMIN_ID'];

try {
    $telegram = new Longman\TelegramBot\Telegram(API_KEY, $BOT_NAME);

    $telegram->enableMySQL($mysql_credentials);

    Log::initErrorLog(__DIR__ . '/logs/error.log');
    Log::initDebugLog(__DIR__ . '/logs/debug.log');
    Log::initUpdateLog(__DIR__ . '/logs/update.log');

    $telegram->enableAdmin($ADMIN_ID);

    $telegram->addCommandsPath(BASE_COMMANDS_PATH . '/CorsairdnbCommands');

    $serverResponse = $telegram->handleGetUpdates();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
