<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConnectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_organization_id',
        'requester_organization_id',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'requester_organization_id', 'id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'receiver_organization_id', 'id');
    }
}
