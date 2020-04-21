<?php
namespace Up\Sabre\Soap;
use Up\Sabre\Config;

class Auth {

    private $config;
    
    public function __construct() {
        $this->config = Config::get()[Config::get()['env']];
    }
    
    public function executeRequest() {
        // libxml_disable_entity_loader(false);
        $client = new \SoapClient(__DIR__."/wsdls/SessionCreateRQ/SessionCreateRQ.wsdl", 
                array("uri" => $this->config['soap'],
                    "location" => $this->config['soap'],
                    "encoding" => "utf-8",
                    "trace" => true,
                    'cache_wsdl' => WSDL_CACHE_NONE));
        $responseHeaders = NULL;
        // dd($client);
        try {
            $client->__soapCall("SessionCreateRQ", 
                    $this->createRequestBody(), 
                    null, 
                    array(Call::createMessageHeader("SessionCreateRQ"), 
                        $this->createSecurityHeader()), 
                    $responseHeaders);
        } catch (SoapFault $e) {
            var_dump($e);
        }
        $result = $responseHeaders["Security"];
        return $result;
    }
    
    private function createSecurityHeader() {
        $securityArray = array(
                "UsernameToken" => array(
                    "Username" => $this->config["userId"],
                    "Password" => $this->config["clientSecret"],
                    "Domain" => $this->config["domain"],
                    "Organization" => $this->config["group"]
                )
        );
        return new \SoapHeader("http://schemas.xmlsoap.org/ws/2002/12/secext", "Security", $securityArray, 1);
    }
    
    private function createRequestBody() {
        $result = array("SessionCreateRQ" => array(
            "POS" => array(
                "Source" => array(
                    "PseudeCityCode" => $this->config["group"]
                )
            )
        ));
        return $result;
    }
}
