<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyCategory extends Model
{
    use HasFactory;

    public const MANUFACTURER = 'manufacturer';
    public const DISTRIBUTOR  = 'distributor';
    public const WHOLESALER   = 'wholesaler';
    public const RETAILER     = 'retailer';
    public const LABORATORY = 'laboratory';
    public const SUPPLIER = 'supplier';
    public const CONSULTANT = 'consultant';

    public const TYPES = [
        self::MANUFACTURER,
        self::DISTRIBUTOR,
        self::WHOLESALER,
        self::RETAILER,
        self::LABORATORY,
        self::SUPPLIER,
        self::CONSULTANT,
    ];

    protected $fillable = ['name'];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
