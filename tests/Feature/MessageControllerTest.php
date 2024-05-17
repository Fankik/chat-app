<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    /**
     * Получение списка сообщений
     */
    public function test_index(): void
    {
        $chat = Chat::factory()
            ->hasMessages(Message::factory())
            ->create();

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

        $response = $this->get(route('messages.index', ['chatId' => $chat->id], false));

        $response->assertOk();
    }

    /**
     * Создание сообщения
     */
    public function test_store(): void
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

        $response = $this->post(route('messages.store', ['chatId' => $chat->id], false), Message::factory()->definition());
       
        $response->assertOk();
    }
}
