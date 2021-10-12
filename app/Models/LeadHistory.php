<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LeadHistory extends Model
{
    use HasFactory;

    protected $table = 'leads_history';

    protected $fillable = [
        'lead_id',
        'status_code',
        'request',
        'response',
    ];

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
    ];

    /**
     * @return HasOne
     */
    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class, 'id');
    }
}
