<?php

namespace App\Jobs\Leads;

use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Send implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $leads = Lead::whereHas('status', function (Builder $query) {
            $query->where('status', LeadStatus::$statusNew);
        });
        foreach ($leads->get() as $value) {
            $integration = new $value->offer->affiliate->integration->handler_class;
            $integration->sendLead($value);
        }
    }
}
