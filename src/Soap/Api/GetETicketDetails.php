<?php
namespace Up\Sabre\Soap\Api;
use Up\Sabre\Soap\XMLSerializer;

use Up\Sabre\Soap\Call;
use Up\Sabre\Config;

class GetETicketDetails {

    private $config;
    
    public function __construct(String $ticket) {
        $this->config = Config::get()[Config::get()['env']];
        $this->ticket = $ticket;
        if(!$this->validateParams()){
          throw new \Exception("Error Processing Request. Required parameter not found!", 1);
          
        }
    }

    public function validateParams()
    {
      if (empty($this->ticket)) {
        return false;
      }

      return true;
    }
    
    public function run() {
        $soapClient = new Call("eTicketCouponLLSRQ");
        $soapClient->setLastInFlow(false);
        // $xmlRequest = $this->getRequest();
        $xmlRequest = $this->stringReq();
        // dd($xmlRequest);
        $result = $soapClient->doCall($xmlRequest);
        return $result;
        // $sharedContext->addResult("BargainFinderMaxRQ", $xmlRequest);
        // $sharedContext->addResult("BargainFinderMaxRS", $soapClient->doCall($sharedContext, $xmlRequest));
        // return new PassengerDetailsNameOnlyActivity();
    }


    private function stringReq(){
        return '
        <eTicketCouponRQ Version="2.0.0" xmlns="http://webservices.sabre.com/sabreXML/2011/10">
            <Ticketing eTicketNumber="'.$this->ticket.'"/>
        </eTicketCouponRQ>
        ';
    }

}