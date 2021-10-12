<?php

namespace App\Integrations;

use App\Models\Lead;
use App\Models\LeadStatus;

class DrCash implements Integration
{

    public static function getAffiliateParams(): array
    {
        return [
            [
                'name' => 'test_aff',
                'text' => 'Test Affiliate',
                'validate' => 'required',
                'type' => 'text'
            ]
        ];
    }

    public static function getOfferParams(): array
    {
        return [
            [
                'name' => 'text_off',
                'text' => 'Test Affiliate',
                'validate' => '',
                'type' => 'text'
            ]
        ];
    }

    public static function sendLead(Lead $lead)
    {
        $lead->addHistory(111, [], []);
        $lead->status->status = LeadStatus::$statusSent;
        $lead->status->save();
    }
}
