<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConnectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_company_id',
        'requester_company_id',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'requester_company_id', 'id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'receiver_company_id', 'id');
    }
}
