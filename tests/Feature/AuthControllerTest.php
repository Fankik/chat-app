<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * Тест авторизации пользователя
     */
    public function test_index(): void
    {
        $userData = User::factory()->definition();

        User::create($userData);

        $response = $this->get(route('auth.index', absolute: false) . "?email={$userData['email']}&password={$userData['password']}");

        $response->assertOk();
    }

    /**
     * Тест регистрации пользователя
     */
    public function test_store(): void
    {
        $response = $this->post(route('auth.store', absolute: false), User::factory()->definition());

        $response->assertOk();
    }


}
