<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель чатов
 */
class Chat extends Model
{
    use HasFactory;

    /**
     * Пользователи чата
     * 
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_users')->orderBy('id');
    }

    /**
     * Сообщения чата
     * 
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Последнее сообщение чата
     * 
     * @return HasOne
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latest('created_at');
    }
}
