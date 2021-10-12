<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Offer extends Model
{
    /**
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($offer) {
            $offer->user_id = auth()->user()->id;
            $offer->hash = Str::uuid();
        });
    }

    /**
     * @var string[]
     */
    protected $fillable = [
        'hash',
        'name',
        'params',
        'affiliate_id',
        'user_id',
    ];

    protected $with = ['affiliate'];

    /**
     * @var array
     */
    protected $casts = [
        'params' => 'object',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * @return HasMany
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * @return BelongsTo
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
