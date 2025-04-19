<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;
use WeStacks\TeleBot\Objects\Message;
use WeStacks\TeleBot\Objects\BotCommand;


class TelegramCustomController extends Controller
{

    private $bot;

    public function __construct()
    {
        $this->bot = new TeleBot(DataSite('telegram_token'));
    }

    public function bot()
    {
        return $this->bot;
    }


    public function setWebhook()
    {
        $url = 'https://' . getDomain() . '/callback/telegram/v1';
        // $url = 'https://gotosub.net/callback/telegram/v1';
        return $this->bot->setWebhook([
            'url' => $url,
        ]);
    }

    public function getWebhookInfo()
    {
        dd($this->bot->getWebhookInfo());
    }

    public function deleteWebhook()
    {
        $this->bot->deleteWebhook();
        return response()->json([
            'status' => 'success',
            'message' => 'delete webhook success'
        ]);
    }

    public function getMe()
    {
        return $this->bot->getMe();
    }

    public function getUpdates()
    {
        dd($this->bot->getUpdates());
    }

    public function getCommands()
    {
        dd($this->bot->getMyCommands());
    }

    public function sendMessage($chat_id, $text, $reply_markup = null)
    {
        return $this->bot->sendMessage([
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]);
    }


    public function sendPhoto($chat_id, $text, $photo)
    {
        return $this->bot->sendPhoto([
            'chat_id' => $chat_id,
            'text' => $text,
            'photo' => $photo,
        ]);
    }



    public function sendDocument($chat_id, $document)
    {
        return $this->bot->sendDocument([
            'chat_id' => $chat_id,
            'document' => $document,
        ]);
    }

    public function sendVideo($chat_id, $video)
    {
        return $this->bot->sendVideo([
            'chat_id' => $chat_id,
            'video' => $video,
        ]);
    }

    public function sendAudio($chat_id, $audio)
    {
        return $this->bot->sendAudio([
            'chat_id' => $chat_id,
            'audio' => $audio,
        ]);
    }

    public function sendVoice($chat_id, $voice)
    {
        return $this->bot->sendVoice([
            'chat_id' => $chat_id,
            'voice' => $voice,
        ]);
    }

    public function sendAnimation($chat_id, $animation)
    {
        return $this->bot->sendAnimation([
            'chat_id' => $chat_id,
            'animation' => $animation,
        ]);
    }

    // command
    public function setMyCommands()
    {
        $commands = [
            new BotCommand([
                'command' => 'help',
                'description' => 'Hướng dẫn sử dụng'
            ]),
            new BotCommand([
                'command' => 'set',
                'description' => 'Xác thực Token'
            ]),
        ];

        $this->bot->setMyCommands([
            'commands' => $commands
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'set commands success'
        ]);
    }

    public function deleteMyCommands()
    {
        $this->bot->deleteMyCommands();
        return response()->json([
            'status' => 'success',
            'message' => 'delete commands success'
        ]);
    }
}
