<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * Тест получения списка пользователей
     */
    public function test_index(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
   
        $response = $this->get(route('users.index', absolute: false));

        $response->assertOk();
    }
}
