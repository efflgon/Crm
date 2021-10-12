<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LeadStatus extends Model
{
    use HasFactory;

    protected $table = 'leads_status';

    protected array $statuses = [
        'new' => 'New',
        'sent' => 'Sent',
        'sending_error' => 'Sending error',
        'deleted' => 'Deleted',
    ];

    protected $fillable = [
        'id',
        'lead_id',
        'status',
        'date_send',
    ];

    static public string $statusNew = 'new';
    static public string $statusSent = 'sent';
    static public string $statusSendingError = 'sending_error';
    static public string $statusDeleted = 'deleted';

    protected $hidden = ['lead_id'];

    /**
     * @return HasOne
     */
    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class, 'id');
    }
}
