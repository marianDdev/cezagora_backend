<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
      'type',
      'number_of_users'
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

}
