<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Conversation;

class TracklistCommand extends UserCommand
{
    protected $name = 'tracklist';
    protected $description = 'Returns tracklist from the given playlist file';
    protected $usage = '/tracklist';
    protected $version = '0.0.1';
    protected $conversation;

    public function execute()
    {
        $message = $this->getMessage();

        $from       = $message->getFrom();
        $user_id    = $from->getId();
        $chat_id    = $message->getChat()->getId();
        $document = $message->getDocument();
        // $message_id = $message->getMessageId();

        $data = [
            'chat_id'             => $chat_id,
            // 'reply_to_message_id' => $message_id,
        ];

        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];
        $state = 0;
        if (isset($notes['state'])) {
            $state = $notes['state'];
        }

        switch ($state) {
            case 0:
                $data['text'] = 'Send me the playlist file';
                $state++;
                $notes['state'] = $state;
                $this->conversation->update();
                break;

            case 1:

                if ($document && ($document->getMimeType()=='audio/x-mpegurl' || $document->getMimeType()=='text/plain') && $document->getFileSize() > 0) {
                    $f = Request::getFile(get_object_vars($document));
                    $f = $f->getResult();
                    $path = $f->file_path;
                    $file = file_get_contents('https://api.telegram.org/file/bot' . API_KEY . '/' . $path);

                    $file = preg_replace('/\/.+\//m', PHP_EOL, $file);
                    $file = preg_replace('/^.:.+\\\/m', '', $file);
                    $file = preg_replace('/\.mp3$/m', '', $file);
                    $file = preg_replace('/\r/m', '', $file);
                    $file = preg_replace('/\n+/m', PHP_EOL, $file);
                    $file = preg_replace('/\n$/m', '', $file);
                    $ar = explode(PHP_EOL, $file);
                    $file = '';
                    for ($i = 0; $i < count($ar); $i++) {
                        $file .= $i+1 . '. ' . $ar[$i] . PHP_EOL;
                    }
                    $data['text'] = $file . PHP_EOL;
                }
                else {
                    $data['text'] = 'File is empty or broken. Please try again.' . PHP_EOL;
                }

                unset($notes['state']);
                $this->conversation->stop();
                break;
        }

        return Request::sendMessage($data);
    }
}
