<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Retailer extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $casts = [
        'products_categories' => AsCollection::class,
        'selling_methods'     => AsCollection::class,
        'marketplaces'        => AsCollection::class,
    ];

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
        'selling_methods',
        'marketplaces',
    ];

    const IN_STORE_METHOD       = 'in_store';
    const ONLINE_SHOP_METHOD    = 'online_shop';
    const ON_MARKETPLACE_METHOD = 'on_marketplaces';

    const SELLING_METHODS = [
        self::IN_STORE_METHOD,
        self::ONLINE_SHOP_METHOD,
        self::ON_MARKETPLACE_METHOD,
    ];

    const AMAZON            = 'amazon';
    const EBAY              = 'ebay';
    const ALIBABA           = 'alibaba';
    const ETSY              = 'etsy';
    const ALIEXPRESS        = 'aliexpress';
    const EMAG              = 'emag';
    const OLX               = 'olx';
    const OTHER_MARKETPLACE = 'other_marketplace';

    const MARKETPLACES = [
        self::AMAZON,
        self::EBAY,
        self::ALIBABA,
        self::ETSY,
        self::ALIEXPRESS,
        self::EMAG,
        self::OLX,
        self::OTHER_MARKETPLACE,
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->getAttributes());
    }
}
