<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConnectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'requester_id',
        'requester_type',
        'requester_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'requester_id', 'id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'receiver_id', 'id');
    }
}
