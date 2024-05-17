<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    /**
     * Получение списка чатов
     */
    public function test_index(): void
    {
        $chat = Chat::factory()->create();

        Sanctum::actingAs(
            User::factory()
                ->hasAttached($chat)
                ->create()
        );

        User::factory()
            ->hasAttached($chat)
            ->create([
                'email' => 'test.testov@test.org',
            ]);

        $response = $this->get(route('chats.index', absolute: false));

        $response->assertOk();
    }

    /**
     * Создание чата
     */
    public function test_store(): void
    {

        Sanctum::actingAs(
            User::factory()->create()
        );

        $user = User::factory()
            ->create([
                'email' => 'test.testov@test.org',
            ]);

        $response = $this->post(route('chats.store', absolute: false), ['user_id' => $user->id]);

        $response->assertOk();
    }
}
