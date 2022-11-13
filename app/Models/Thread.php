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

    protected $fillable = ['first_organization_id', 'second_organization_id'];

    public function firstOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'first_organization_id', 'id');
    }

    public function secondOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'second_organization_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
