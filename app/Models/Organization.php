<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string  $type
 * @property integer $id
 */
class Organization extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $casts = [
        'products_categories' => AsCollection::class,
        'selling_methods'     => 'array',
        'company_types'        => "array",
        'marketplaces'        => AsCollection::class,
    ];

    protected $fillable = [
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
        'company_types',
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

    const MANUFACTURER_TYPE = 'manufacturer';
    const DISTRIBUTOR_TYPE  = 'distributor';
    const WHOLESALER_TYPE   = 'wholesaler';
    const RETAILER_TYPE     = 'retailer';

    const TYPES = [
        self::MANUFACTURER_TYPE,
        self::DISTRIBUTOR_TYPE,
        self::WHOLESALER_TYPE,
        self::RETAILER_TYPE,
    ];

    const CONTINENTS = ['Africa', 'Asia', 'Europe', 'North America', 'Oceania', 'South America'];

    public function retailer(): HasOne
    {
        return $this->hasOne(Retailer::class);
    }

    public function distributor(): HasOne
    {
        return $this->hasOne(Distributor::class);
    }

    public function manufacturer(): HasOne
    {
        return $this->hasOne(Manufacturer::class);
    }

    public function wholesaler(): HasOne
    {
        return $this->hasOne(Wholesaler::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function connectionRequestsSent(): HasMany
    {
        return $this->hasMany(ConnectionRequest::class, 'requester_id', 'id');
    }

    public function connectionRequestsReceived(): HasMany
    {
        return $this->hasMany(ConnectionRequest::class, 'receiver_id', 'id');
    }

    public function connections(): HasMany
    {
        return $this->hasMany(Connection::class);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'followed_organization_id', 'id');
    }

    public function followings(): HasMany
    {
        return $this->hasMany(Follower::class, 'follower_organization_id', 'id');
    }

    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->getAttributes());
    }

    public function threads(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_organization_id', 'id');
    }
}
