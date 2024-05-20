<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Контроллер пользователя
 */
class UserController
{
    /**
     * Список пользователей
     * 
     * Отдается порционно по 20 пользователей.
     * 
     * Для получения следующих страниц необходимо передать `page` с номером страницы.
     *  
     * @param Request $request
     * @return Collection
     *
     * @queryParam page Номер страницы. Example: 1
     * @responseFile status=200 scenario="success" storage/responses/users/index.200.json
     * @authenticated
     */
    public function index(Request $request): Collection
    {
        $page = $request->input('page') ?? 1;
        $limit = 20;
        $skip = ($page - 1) * $limit;

        $users = User::limit($limit)->skip($skip)->get();

        $users->makeHidden(['created_at', 'updated_at']);

        return $users;
    }
}
