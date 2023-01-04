<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CosmeticsEvent extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'is_live',
        'title',
        'description',
        'start_at',
        'end_at',
        'country',
        'city',
        'address',
        'host',
        'link',
        'credit',
    ];
}
