<?php

namespace App\Models;

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

    protected $fillable = [
      'type',
      'number_of_users',
      'has_details_completed'
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
