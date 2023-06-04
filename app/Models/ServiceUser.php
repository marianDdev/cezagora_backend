<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ServiceUser extends Pivot
{
    use HasFactory;

    protected $fillable   = ['user_id', 'thread_id'];
    public    $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasMany(User::class);
    }

    public function service(): HasOne
    {
        return $this->hasOne(Service::class);
    }
}
