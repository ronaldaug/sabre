<?php

namespace Up\Sabre\Soap\Api;
use Up\Sabre\Soap\XMLSerializer;

use Up\Sabre\Soap\Call;
use Up\Sabre\Config;

class CancelItinerarySegment{

    private $config;
    
    public function __construct(String $params) {
        $this->config = Config::get()[Config::get()['env']];
        $this->params = $params;
        if(!$this->validateParams()){
          throw new \Exception("Error Processing Request. Required parameter not found!", 1);
          
        }
    }


    public function validateParams()
    {
    //   if (empty($this->params)) {
    //     return false;
    //   }
    //   if (empty($this->params['OriginDestinationInformation']) || empty($this->params['PassengerTypeQuantity'])) {
    //     return false;
    //   }

      return true;
    }
    
    public function run() {
        $soapClient = new Call("OTA_CancelLLSRQ");
        $soapClient->setLastInFlow(true);
        $xmlRequest = $this->getRequest();
        $result = $soapClient->doCall($xmlRequest);
        return $result;
    }

    private function getRequest() {
        $version   = $this->config['CancelItinerarySegment'];
        $pcc       = $this->config['group'];
        $request = '<OTA_CancelRQ Version="2.0.2" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <Segment Type="entire"/>
    </OTA_CancelRQ>';
        return $request;
    }

}