<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Wholesaler extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $casts = ['products_categories' => AsCollection::class];

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'continent',
        'country',
        'city',
        'address',
        'products_categories',
        'has_list_uploaded'
    ];

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->getAttributes());
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
