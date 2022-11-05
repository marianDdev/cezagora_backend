<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'connected_organization_id',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function connectedOrganization()
    {
        return $this->belongsTo(Organization::class, 'connected_organization_id', 'id');
    }
}
