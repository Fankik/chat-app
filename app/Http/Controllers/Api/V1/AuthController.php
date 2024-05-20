<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Контроллер авторизации и регистрации пользователя
 */
class AuthController
{
    /**
     * Авторизация
     * 
     * Для прохождения аутентификации необходимо указать `email` и `password` в теле запроса.
     * 
     * После пройденной аутентификации создается accessToken.
     * 
     * @param Request $request
     * @return Response|ResponseFactory
     * 
     * @responseFile status=200 scenario="success" storage/responses/auth/index.200.json
     * @responseFile status=403 scenario="invalid email or password" storage/responses/auth/index.403.json
     * @responseFile status=422 scenario="validation fail" storage/responses/auth/validate_fail.json
     */
    public function index(Request $request): Response|ResponseFactory
    {
        // Query parameters
        $credentials = $request->validate([
            //Email Example: ivan.ivanov@test.org
            'email' => ['required', 'email:rfc,dns', 'string', 'max:255'],
            //Пароль Example: test
            'password' => ['required', 'max:32']
        ]);

        if (Auth::attempt($credentials)) {
            Auth::user()->createToken('accessToken', ['*'], now()->addWeek());

            return response(['message' => 'Authorization is successful']);
        } else {
            return response(['message' => 'Invalid email or password'], 403);
        }
    }

    /**
     * Регистрация
     * 
     * Для регистрации пользователя необходимо передать `first_name`, `last_name`, `email`, `password` в теле запроса.
     * 
     * @param Request $request
     * @return Response|ResponseFactory
     * 
     * @responseFile status=200 scenario="success" storage/responses/auth/store.200.json
     * @responseFile status=422 scenario="validation fail" storage/responses/auth/validate_fail.json
     */
    public function store(Request $request): Response|ResponseFactory
    {
        $userData = $request->validate([
            //Имя Example: Иван
            'first_name' => ['required', 'string', 'max:255'],
            //Фамилия Example: Иванов
            'last_name' => ['required', 'string', 'max:255'],
            //Email Example: ivan.ivanov@test.org
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            //Пароль Example: test
            'password' => ['required', 'max:32']
        ]);

        $userData['password'] = Hash::make($userData['password']);

        User::create($userData);

        return response(['message' => 'Registration is successful']);
    }
}
