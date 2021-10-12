<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    /**
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($lead) {
            $lead->status()->create([
                'lead_id' => $lead->id,
                'status' => LeadStatus::$statusNew
            ]);
            $lead->save();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'offer_hash',
        'ip',
        'area_code',
        'name',
        'surname',
        'email',
        'phone',
        'sub1',
        'sub2',
        'sub3',
        'sub4',
        'sub5',
        'sub6',
    ];

    protected $with = ['status', 'offer', ];

    /**
     * @return HasOne
     */
    public function offer(): HasOne
    {
        return $this->hasOne(Offer::class, 'hash', 'offer_hash');
    }

    public function status(): HasOne
    {
        return $this->hasOne(LeadStatus::class, 'lead_id');
    }

    public function history(): HasOne
    {
        return $this->hasOne(LeadHistory::class, 'lead_id');
    }

    public function addHistory(int $status_code, array $request, array $response)
    {
        $this->history()->create([
            'lead_id' => $this->id,
            'status_code' => $status_code,
            'request' => $request,
            'response' => $response
        ]);
    }
}
