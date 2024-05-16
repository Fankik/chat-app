<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Контроллер сообщений
 */
class MessageController
{
    /**
     * Создание сообщения
     *  
     * Создается сообщение в чате. 
     * Необходимо указать в URI `chatId` и передать `text`в теле запроса. 
     * 
     * @param int $chatId
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @responseFile status=200 scenario="success" storage/responses/messages/store.200.json
     * @responseFile status=404 scenario="chat not found" storage/responses/messages/store.404.json
     * @responseFile status=422 scenario="validation fail" storage/responses/messages/validate_fail.json
     * @authenticated
     */
    public function store(int $chatId, Request $request)
    {
        $message = $request->validate([
            'text' => ['required', 'string', 'max:255']
        ]);

        $user = Auth::user();
        $chat = $user->chats()->where('chats.id', $chatId)->first();

        if ($chat === null) {
            return response(['message' => 'Chat not found'], 404);
        }

        $message['chat_id'] = $chat->id;
        $message['text'] = strip_tags($message['text']);

        $message = new Message($message);

        return response(['message' => 'Message created']);
    }
}
