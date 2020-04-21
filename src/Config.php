<?php

namespace Up\Sabre;

class Config{
    public static function get(){
        return [
            'env' => env('SABRE_ENV', 'test'),
            'test' => [
                'soap' => 'https://sws-crt.cert.havail.sabre.com',
                'rest' => 'https://api-crt.cert.havail.sabre.com',
                'userId' => env('SABRE_CLIENT_ID', 'ctyk3ou66mgrtghg'),
                'group' => env('SABRE_PCC', 'DEVCENTER'),
                'domain' => env('SABRE_DOMAIN', 'EXT'),
                'clientSecret' => env('SABRE_SECRET', 'Hs26JuSi'),
                'formatVersion' => 'V1',
                'OTA_PingRQVersion' => '1.0.0',
                'TravelItineraryReadRQVersion' => '3.6.0',
                'PassengerDetailsRQVersion' => '3.2.0',
                'IgnoreTransactionLLSRQVersion' => '2.0.0',
                'CancelItinerarySegment' => '2.0.2',
                'GetReservation' => '1.19.0',
                'BargainFinderMaxRQVersion' => '5.2.0',
                'EnhancedAirBookRQVersion' => '3.2.0'
            ],
            'production' => [
                'soap' => 'https://webservices.havail.sabre.com',
                'rest' => 'https://api.havail.sabre.com',
                'userId' => env('SABRE_CLIENT_ID', 'ctyk3ou66mgrtghg'),
                'group' => env('SABRE_PCC', 'DEVCENTER'),
                'domain' => env('SABRE_DOMAIN', 'EXT'),
                'clientSecret' => env('SABRE_SECRET', 'Hs26JuSi'),
                'formatVersion' => 'V1',
                'OTA_PingRQVersion' => '1.0.0',
                'TravelItineraryReadRQVersion' => '3.6.0',
                'PassengerDetailsRQVersion' => '3.2.0',
                'IgnoreTransactionLLSRQVersion' => '2.0.0',
                'BargainFinderMaxRQVersion' => '5.2.0',
                'CancelItinerarySegment' => '2.0.2',
                'GetReservation' => '1.19.0',
                'EnhancedAirBookRQVersion' => '3.2.0'
            ],
        ];
    }
}
 