<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ChatIndexResource;
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
     * Получение списка чатов
     *  
     * Отдается порционно по 20 чатов.
     * 
     * Для получения следующих страниц необходимо передать `page` с номером страницы.
     * 
     * @param Request $request
     * @return JsonResource
     *
     * @queryParam page Номер страницы. Example: 2
     * @responseFile status=200 scenario="success" storage/responses/chats/index.200.json 
     * @authenticated
     */
    public function index(Request $request): JsonResource
    {
        $page = $request->input('page') ?? 1;
        $limit = 20;
        $skip = ($page - 1) * $limit;

        $user = Auth::user();

        $chats = $user->chats()->with('lastMessage', 'users')
            ->skip($skip)
            ->limit($limit)
            ->get()
            ->sortByDesc('lastMessage.created_at');

        ChatIndexResource::withoutWrapping();

        return ChatIndexResource::collection($chats);
    }

    /**
     * Создание чата
     *  
     * Создается чат с передаваемым пользователем, если его не существует.
     * 
     * Для создания чата с пользователем необходимо в параметрах передать `user_id`. 
     * 
     * @param Request $request
     * @return Response|ResponseFactory
     *
     * @responseFile status=200 scenario="success" storage/responses/chats/store.200.json
     * @responseFile status=404 scenario="user not found" storage/responses/chats/store.404.json
     * @responseFile status=409 scenario="chat already exist" storage/responses/chats/store.409.json
     * @responseFile status=409 scenario="chat with yourself" storage/responses/chats/store_yourself.409.json
     * @authenticated
     */
    public function store(Request $request): Response|ResponseFactory
    {
        $validate = $request->validate([
            //user_id Example: 2
            'user_id' => ['required', 'int'],
        ]);

        $userId = $validate['user_id'];

        $user = User::find($userId);
        $authUser = Auth::user();

        if ($user === null) {
            return response(['message' => 'User not found'], 404);
        }

        if ($user->id === $authUser->id) {
            return response(['message' => 'It is impossible to create a chat with yourself'], 409);
        }

        $chat = Chat::whereHas(
            'users',
            function ($query) use ($user, $authUser) {
                $query->whereIn('users.id', [$user->id, $authUser->id]);
            },
            '=',
            2
        )->first();

        if ($chat === null) {
            $chat = Chat::create();
            $chat->users()->attach([$user->id, $authUser->id]);

            return response(['message' => 'Chat created']);
        }

        return response(['message' => 'Chat already exist'], 409);
    }
}
