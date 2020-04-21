<?php
namespace Up\Sabre\Soap;
use Up\Sabre\Soap\Call;
use Up\Sabre\Soap\Api\GetReservation;
use Up\Sabre\Soap\Api\BargainFinderMax;
use Up\Sabre\Soap\Api\GetETicketDetails;
use Up\Sabre\Soap\Api\AlternateAirportShop;
use Up\Sabre\Soap\Api\GetCurrencyConversion;
use Up\Sabre\Soap\Api\CancelItinerarySegment;

class Api{

    public function call($action, $request)
    {
        $soapClient = new Call($action);
        $soapClient->setLastInFlow(false);
        $result = $soapClient->doCall($request);
        return $result;
    }
    public function BargainFinderMax(Array $params)
    {
        $BargainFinderMax = new BargainFinderMax($params);
        return $BargainFinderMax->run();
    }

    public function AlternateAirportShop(Array $params)
    {
        $AlternateAirportShop = new AlternateAirportShop($params);
        return $AlternateAirportShop->run();
    }

    public function GetReservation(String $params){
        $getReservation = new GetReservation($params);
        return $getReservation->run();
    }
    
    public function CancelItinerarySegment(String $params)
    {
        $CancelItinerarySegment = new CancelItinerarySegment($params);
        return $CancelItinerarySegment->run();
    }
    public function GetCurrencyConversion(String $params)
    {
        $GetCurrencyConversion = new GetCurrencyConversion($params);
        return $GetCurrencyConversion->run();
    }
    public function GetETicketDetails(String $ticket)
    {
        $GetETicketDetails = new GetETicketDetails($ticket);
        return $GetETicketDetails->run();
    }
}