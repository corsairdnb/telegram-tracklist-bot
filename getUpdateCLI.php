#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Longman\TelegramBot\TelegramLog as Log;

$API_KEY = '373984740:AAE-x5yVlszhdNUhDB6xCFWDKpw6k3Lw2AY';
$BOT_NAME = 'corsairdnb_tracklist_bot';
$mysql_credentials = [
    'host'     => 'localhost',
    'user'     => 'root',
    'password' => 'root',
    'database' => 'telegram_bot_tracklist',
];
$ADMIN_ID = 108894177; // corsairdnb

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    // Enable MySQL
    $telegram->enableMySQL($mysql_credentials);

    //var_dump($telegram); die();

//    Longman\TelegramBot\TelegramLog::initialize($your_external_monolog_instance);
    Log::initErrorLog(__DIR__ . '/logs/error.log');
    Log::initDebugLog(__DIR__ . '/logs/debug.log');
    Log::initUpdateLog(__DIR__ . '/logs/update.log');

    $telegram->enableAdmin($ADMIN_ID);

    $telegram->addCommandsPath(BASE_COMMANDS_PATH . '/CorsairdnbCommands');

    $serverResponse = $telegram->handleGetUpdates();

//    if ($serverResponse->isOk()) {
//        LOG::debug('_____________________________________________');
//        $updateCount = count($serverResponse->getResult());
//        echo date('Y-m-d H:i:s', time()) . ' - Processed ' . $updateCount . ' updates';
//    } else {
//        echo date('Y-m-d H:i:s', time()) . ' - Failed to fetch updates' . PHP_EOL;
//        echo $serverResponse->printError();
//    }



} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    echo $e;
}
