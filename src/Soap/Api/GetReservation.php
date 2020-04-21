<?php

namespace Up\Sabre\Soap\Api;
use Up\Sabre\Soap\XMLSerializer;

use Up\Sabre\Soap\Call;
use Up\Sabre\Config;

class GetReservation{

    private $config;
    private $params;
    
    public function __construct(String $params) {
        $this->config = Config::get()[Config::get()['env']];
        $this->params = $params;
        if(!$this->validateParams()){
          throw new \Exception("Error Processing Request. Required parameter not found!", 1);
        }
    }


    public function validateParams()
    {
      if (empty($this->params)) {
        return false;
      }

      return true;
    }
    
    public function run() {
        $soapClient = new Call("GetReservationRQ");
        $soapClient->setLastInFlow(false);
        $xmlRequest = $this->getRequest();
        // header("Content-type: text/xml");
        // echo $xmlRequest;die();
        $result = $soapClient->doCall($xmlRequest);
        return $result;
        // $sharedContext->addResult("BargainFinderMaxRQ", $xmlRequest);
        // $sharedContext->addResult("BargainFinderMaxRS", $soapClient->doCall($sharedContext, $xmlRequest));
        // return new PassengerDetailsNameOnlyActivity();
    }

    private function getRequest() {
        $version = $this->config['GetReservation'];
        $request = '
        <ns7:GetReservationRQ xmlns:ns7="http://webservices.sabre.com/pnrbuilder/v1_19" Version="1.19.0">
        <ns7:Locator>'.$this->params.'</ns7:Locator>
        <ns7:RequestType>Stateful</ns7:RequestType>
        <ns7:ReturnOptions PriceQuoteServiceVersion="3.2.0">
            <ns7:SubjectAreas>
                <ns7:SubjectArea>PRICE_QUOTE</ns7:SubjectArea>
            </ns7:SubjectAreas>
            <ns7:ViewName>Simple</ns7:ViewName>
            <ns7:ResponseFormat>STL</ns7:ResponseFormat>
        </ns7:ReturnOptions>
    </ns7:GetReservationRQ>';
        return $request;
    }

}