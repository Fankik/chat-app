<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Контроллер чатов
 */
class ChatController
{
    /**
     * Создание чата
     *  
     * Создается чат с передаваемым пользователем, если его не существует.
     * 
     * @var int $userId
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @responseFile status=200 scenario="success" storage/responses/chats/store.200.json
     * @responseFile status=200 scenario="user not found" storage/responses/chats/store.404.json
     * @responseFile status=200 scenario="chat already exist" storage/responses/chats/store.409.json
     * @authenticated
     */
    public function store(int $userId)
    {
        $user = User::find($userId);
        $authUser = Auth::user();

        if ($user === null) {
            return response(['message' => 'User not found'], 404);
        }

        $chat = Chat::whereHas(
            'users',
            function ($query) use ($user, $authUser) {
                $query->whereIn('users.id', [$user->id, $authUser->id]);
            }
        )->first();

        if ($chat === null) {
            $chat = Chat::create();
            $chat->users()->attach([$user->id, $authUser->id]);

            return response(['message' => 'Chat created']);
        }

        return response(['message' => 'Chat already exist'], 409);
    }
}
