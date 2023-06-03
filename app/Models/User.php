<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Company $company
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'company_id',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function isRetailer()
    {
        return $this->company->type = Company::RETAILER_TYPE;
    }

    public function isDistributor()
    {
        return $this->company->type = Company::DISTRIBUTOR_TYPE;
    }

    public function isWholeSaler()
    {
        return $this->company->type = Company::WHOLESALER_TYPE;
    }

    public function isManufacturer()
    {
        return $this->company->type = Company::MANUFACTURER_TYPE;
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

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function hasConnections(): bool
    {
        return !is_null($this->connections);
    }

    public function followings(): HasMany
    {
        return $this->hasMany(Follower::class);
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

    public function isAdmin(): bool
    {
        return $this->is_admin == true;
    }
}
