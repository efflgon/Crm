<?php

namespace App\Integrations;

use App\Models\Lead;

interface Integration
{
    public static function getAffiliateParams(): array;
    public static function getOfferParams(): array;
    public static function sendLead(Lead $lead);
}
