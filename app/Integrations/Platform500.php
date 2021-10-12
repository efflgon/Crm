<?php

namespace App\Integrations;

use App\Models\Lead;
use App\Models\LeadStatus;

class Platform500 implements Integration
{

    public static function getAffiliateParams(): array
    {
        return [
            [
                'name' => 'api_url',
                'text' => 'URL отправки',
                'validate' => 'required',
                'type' => 'text'
            ],
            [
                'name' => 'token',
                'text' => 'Токен',
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
            'user_ip' => $lead->ip,
            'first_name' => $lead->name,
            'last_name' => $lead->lastname,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'country' => $lead->area_code,
            'aff_sub' => $lead->sub1,
            'aff_sub2' => $lead->sub2,
            'aff_sub3' => $lead->sub3,
            'aff_sub4' => $lead->sub4,
            'aff_sub5' => $lead->sub5,
        ];


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $lead->offer->affiliate->params->api_url.'/api/v1/affiliates/leads',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query(array(
                'token' => $lead->offer->affiliate->params->token,
                'profile[first_name]' => $lead->name,
                'profile[last_name]' => $lead->surname,
                'profile[email]' => $lead->email,
                'profile[phone]' => $lead->phone,
                'profile[password]' => 'wedcemck',
                'ip' => $lead->ip,
                'tp_aff_sub' => $lead->sub1,
                'tp_aff_sub2' => $lead->sub2,
                'tp_aff_sub3' => $lead->sub3,
                'tp_aff_sub4' => $lead->sub4,
                'tp_aff_sub5' => $lead->sub5,
            )),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        /**
         * Getting response / httpCode
         */
        $res = curl_exec($curl);
        
        $lead->addHistory(curl_getinfo($curl, CURLINFO_HTTP_CODE), $arr, json_decode($res, JSON_UNESCAPED_UNICODE));
        $lead->status->status = curl_getinfo($curl, CURLINFO_HTTP_CODE) < 300 ? LeadStatus::$statusSent : LeadStatus::$statusSendingError;
        $lead->status->save();
        curl_close($curl);
    }
}
