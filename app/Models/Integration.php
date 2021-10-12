<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Integration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'handler_class',

    ];

    protected $appends = [
        'affiliateParams',
        'offerParams',
    ];

    /**
     * @return HasMany
     */
    public function affiliates(): HasMany
    {
        return $this->hasMany(Affiliate::class);
    }

    public function getAffiliateParamsAttribute(): array
    {
        return (array) $this->handler_class::getAffiliateParams();
    }

    public function getOfferParamsAttribute(): array
    {
        return (array) $this->handler_class::getOfferParams();
    }
}
