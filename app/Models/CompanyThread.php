<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyThread extends Pivot
{
    protected $fillable   = ['company_id', 'thread_id'];
    public    $timestamps = false;

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function thread(): HasOne
    {
        return $this->hasOne(Thread::class);
    }
}
