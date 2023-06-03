<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_company_id',
        'followed_company_id',
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'follower_company_id', 'id');
    }

    public function followed(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'followed_company_id', 'id');
    }
}
