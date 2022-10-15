<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Organization $organization
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'organization_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function isRetailer()
    {
        return $this->organization->type = Organization::RETAILER_TYPE;
    }

    public function isDistributor()
    {
        return $this->organization->type = Organization::DISTRIBUTOR_TYPE;
    }

    public function isWholeSaler()
    {
        return $this->organization->type = Organization::WHOLESALER_TYPE;
    }

    public function isManufacturer()
    {
        return $this->organization->type = Organization::MANUFACTURER_TYPE;
    }

    public function connectionRequests(): HasMany
    {
        return $this->hasMany(ConnectionRequest::class);
    }

    public function hasConnectionRequests(): bool
    {
        return !is_null($this->connectionRequests);
    }
}
