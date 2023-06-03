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

    protected $fillable = ['first_company_id', 'second_company_id'];

    public function firstCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'first_company_id', 'id');
    }

    public function secondCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'second_company_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
