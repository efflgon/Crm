<?php

namespace App\Integrations;

use App\Models\Lead;
use App\Models\LeadStatus;

class ClickMate implements Integration
{

    public static function getAffiliateParams(): array
    {
        return [
            [
                'name' => 'hash',
                'text' => 'URL HASH',
                'validate' => 'required',
                'type' => 'text'
            ]
        ];
    }

    public static function getOfferParams(): array
    {
        return [
        ];
    }

    public static function sendLead(Lead $lead)
    {
        $arr = [
            'name' => $lead->name,
            'lastname' => $lead->lastname,
            'email' => $lead->email,
            'password' => 'cwkrvjew',
            'phone' => $lead->phone,
            'country_id' => $lead->area_code,
            'specific_data' => [
                'track_code' => $lead->sub1,
                'ip' => $lead->ip
            ],
            'aff_sub' => $lead->sub1,
            'aff_sub2' => $lead->sub2,
            'aff_sub3' => $lead->sub3,
            'aff_sub4' => $lead->sub4,
            'aff_sub5' => $lead->sub5,
        ];

        $ch = curl_init('https://bitcoin-code.link/users/ajax/' . $lead->offer->affiliate->params->hash);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arr, JSON_UNESCAPED_UNICODE));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);

        $history = [
            'url' => 'https://bitcoin-code.link/users/ajax/' . $lead->offer->affiliate->params->hash,
            'params' => $arr,
        ];

        $lead->addHistory(curl_getinfo($ch, CURLINFO_HTTP_CODE), $history, json_decode($res, JSON_UNESCAPED_UNICODE));
        $lead->status->status = curl_getinfo($ch, CURLINFO_HTTP_CODE) < 300 ? LeadStatus::$statusSent : LeadStatus::$statusSendingError;
        $lead->status->save();
        curl_close($ch);
    }
}
