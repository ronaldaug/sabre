<?php
namespace Up\Sabre\Rest;
use Up\Sabre\Rest\Api\BargainFinderMax;
use Up\Sabre\Rest\Api\AlternateAirportShop;
use Up\Sabre\Rest\Api\AirlineLookup;
use Up\Sabre\Rest\Api\CreatePassengerNameRecord;
use Up\Sabre\Rest\Api\EnhancedAirTicket;
use Up\Sabre\Rest\Call;
class Api{

    public function call($path, $request)
    {
        $Call = new Call();
        $result = $Call->executePostCall($path, $request);
        return $result;
    }
    public function BargainFinderMax(Array $params)
    {
        $BargainFinderMax = new BargainFinderMax($params);
        return $BargainFinderMax->run();
    }
    public function AlternateAirportShop(Array $params)
    {
        $BargainFinderMax = new AlternateAirportShop($params);
        return $BargainFinderMax->run();
    }
    public function CreatePassengerNameRecord(Array $params)
    {
        $CreatePassengerNameRecord = new CreatePassengerNameRecord($params);
        return $CreatePassengerNameRecord->run();
    }
    public function EnhancedAirTicket(String $pnr)
    {
        $EnhancedAirTicket = new EnhancedAirTicket($pnr);
        return $EnhancedAirTicket->run();
    }
    public function AirlineLookup(Array $params)
    {
        $AirlineLookup = new AirlineLookup($params);
        return $AirlineLookup->run();
    }
}