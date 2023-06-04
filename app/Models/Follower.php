<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'followed_user_id',
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function followed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'followed_user_id', 'id');
    }
}
