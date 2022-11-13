<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationThread extends Pivot
{
    protected $fillable   = ['organization_id', 'thread_id'];
    public    $timestamps = false;

    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class);
    }

    public function thread(): HasOne
    {
        return $this->hasOne(Thread::class);
    }
}
