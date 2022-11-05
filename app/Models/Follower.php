<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_organization_id',
        'followed_organization_id',
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'follower_organization_id', 'id');
    }

    public function followed(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'followed_organization_id', 'id');
    }
}
