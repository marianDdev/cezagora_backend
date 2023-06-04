<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property Company $company
 */
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

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

    public function connections(): HasMany
    {
        return $this->hasMany(Connection::class, 'user_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function threads(): BelongsToMany
    {
        return $this->belongsToMany(Thread::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'followed_user_id', 'id');
    }

    public function followings(): HasMany
    {
        return $this->hasMany(Follower::class, 'user_id', 'id');
    }

    public function connectionRequestsSent()
    {
        return $this->hasMany(ConnectionRequest::class, 'user_id', 'id');
    }

    public function connectionRequestsReceived()
    {
        return $this->hasMany(ConnectionRequest::class, 'receiver_user_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin == true;
    }
}
