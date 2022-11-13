<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $type
 * @property integer  $id
 */
class Organization extends Model
{
    use HasFactory;

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

    const MANUFACTURER_TYPE = 'manufacturer';
    const DISTRIBUTOR_TYPE = 'distributor';
    const WHOLESALER_TYPE = 'wholesaler';
    const RETAILER_TYPE = 'retailer';

    const TYPES = [
        self::MANUFACTURER_TYPE,
        self::DISTRIBUTOR_TYPE,
        self::WHOLESALER_TYPE,
        self::RETAILER_TYPE
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

}
