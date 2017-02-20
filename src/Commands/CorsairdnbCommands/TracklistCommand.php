<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
//use Longman\TelegramBot\Entities\File;
//use Longman\TelegramBot\Entities\PhotoSize;
//use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Request;

/**
 * User "/whoami" command
 */
class TracklistCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'tracklist';

    /**
     * @var string
     */
    protected $description = 'Returns tracklist from the given .m3u file';

    /**
     * @var string
     */
    protected $usage = '/tracklist';

    /**
     * @var string
     */
    protected $version = '0.0.1';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $from       = $message->getFrom();
        $user_id    = $from->getId();
        $chat_id    = $message->getChat()->getId();
        $message_id = $message->getMessageId();

        $data = [
            'chat_id'             => $chat_id,
            'reply_to_message_id' => $message_id,
        ];

        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action'  => 'typing',
        ]);

        $caption = 'hello world! it is tracklist bot';

        $data['text'] = $caption;

        return Request::sendMessage($data);
    }
}
