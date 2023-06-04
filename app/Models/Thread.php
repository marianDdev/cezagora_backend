<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Thread extends Model
{
    use HasFactory;

    protected $fillable = ['first_user_id', 'second_user_id'];

    public function firstUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_user_id', 'id');
    }

    public function secondUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_user_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
