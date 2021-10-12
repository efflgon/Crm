<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class SendLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send leads to affiliates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        \App\Jobs\Leads\Send::dispatch();
        $leads = Lead::whereHas('status', function (Builder $query) {
            $query->where('status', LeadStatus::$statusNew);
        });
        foreach ($leads->get() as $value) {
            $integration = new $value->offer->affiliate->integration->handler_class;
            $integration->sendLead($value);
        }
        return 0;
    }
}
