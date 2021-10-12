<?php

namespace App\Integrations;

use App\Models\Lead;
use App\Models\LeadStatus;

class Avix implements Integration
{

    public static function getAffiliateParams(): array
    {
        return [
            [
                'name' => 'url',
                'text' => 'URL для отправки',
                'validate' => 'required',
                'type' => 'text'
            ],
            [
                'name' => 'aff_id',
                'text' => 'ID aff',
                'validate' => 'required',
                'type' => 'text'
            ]
        ];
    }

    public static function getOfferParams(): array
    {
        return [
            [
                'name' => 'offer_id',
                'text' => 'ID оффера',
                'validate' => 'required',
                'type' => 'text'
            ]
        ];
    }

    public static function sendLead(Lead $lead)
    {
        $URL = 'https://' . $lead->offer->affiliate->params->url . '/tracker';
        $arr = array(
            'first_name' => $lead->name,
            'last_name' => $lead->surname,
            'email' => $lead->email,
            'password' => 'fowpcnrt',
            'phonecc' => '+34',
            'phone' => '1231242341',
            'country' => $lead->area_code,
            'user_ip' => $lead->ip,
            'aff_sub1' => $lead->sub1,
            'aff_sub2' => $lead->sub2,
            'aff_sub3' => $lead->sub3,
            'aff_id' => $lead->offer->affiliate->params->aff_id,
            'offer_id' => $lead->offer->params->offer_id,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL . '?' . http_build_query($arr));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);

        $lead->addHistory(curl_getinfo($ch, CURLINFO_HTTP_CODE), $arr, [json_decode($response, JSON_UNESCAPED_UNICODE)]);
        $lead->status->status = curl_getinfo($ch, CURLINFO_HTTP_CODE) < 300 ? LeadStatus::$statusSent : LeadStatus::$statusSendingError;
        $lead->status->save();
        curl_close($ch);
    }
}
