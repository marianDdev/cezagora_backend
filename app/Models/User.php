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

    public function connections(): HasMany
    {
        return $this->hasMany(Connection::class);
    }

    public function hasConnections(): bool
    {
        return !is_null($this->connections);
    }

    public function followings(): HasMany
    {
        return $this->hasMany(Following::class);
    }

    public function hasFollowings(): bool
    {
        return !is_null($this->followings);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class);
    }

    public function hasFollowers(): bool
    {
        return !is_null($this->followers);
    }
}
