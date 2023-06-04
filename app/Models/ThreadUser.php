<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ThreadUser extends Pivot
{
    protected $fillable   = ['user_id', 'thread_id'];
    public    $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function thread(): HasOne
    {
        return $this->hasOne(Thread::class);
    }
}
