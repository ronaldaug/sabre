<?php
namespace Up\Sabre\Soap\Api;
use Up\Sabre\Soap\XMLSerializer;
use Up\Sabre\Config;
use Up\Sabre\Soap\Call;

class GetCurrencyConversion {

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
      if (empty($this->params)) {
        return false;
      }
      return true;
    }
    
    public function run() {
        $soapClient = new Call("DisplayCurrencyLLSRQ");
        $soapClient->setLastInFlow(false);
        $xmlRequest = $this->getRequest();

        // header("Content-Type: text/xml");

        $result = $soapClient->doCall($xmlRequest);
        // dd($result);
        return $result;
        // $sharedContext->addResult("BargainFinderMaxRQ", $xmlRequest);
        // $sharedContext->addResult("BargainFinderMaxRS", $soapClient->doCall($sharedContext, $xmlRequest));
        // return new PassengerDetailsNameOnlyActivity();
    }

    private function getRequest() {
        $request = array("DisplayCurrencyRQ" => array(
                "_attributes" => array("Version" => '2.1.0', "ReturnHostCommand" => 'false', "TimeStamp" => date('Y-m-d').'T'.date('H:i:s').'+00:00'),
                "_namespace" => "http://webservices.sabre.com/sabreXML/2011/10",
                
                "CountryCode" => $this->params,
                "CurrencyCode" => 'SGD',
            )
        );
        return XMLSerializer::generateValidXmlFromArray($request);
    }

}