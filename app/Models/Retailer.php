<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
    ];

    const IN_STORE_METHOD     = 'in_store';
    const ONLINE_SHOP_METHOD    = 'online_shop';
    const ON_MARKETPLACE_METHOD = 'on_markets';

    const SELLING_METHODS = [
        self::IN_STORE_METHOD,
        self::ONLINE_SHOP_METHOD,
        self::ON_MARKETPLACE_METHOD,
];

    const AMAZON = 'amazon';
    const EBAY = 'ebay';
    const ALIBABA = 'alibaba';
    const ETSY = 'etsy';
    const ALIEXPRESS = 'aliexpress';
    const EMAG = 'emag';
    const OLX = 'olx';
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
}
