<?php
namespace Up\Sabre\Soap;
use Up\Sabre\Config;

class IgnoreTransactionRequest {

    private $config;
    
    public function __construct() {
        $this->config = Config::get()[Config::get()['env']];
    }
    
    public function executeRequest($security) {
        $client = new \SoapClient(__DIR__ ."/wsdls/IgnoreTransactionLLSRQ/IgnoreTransactionLLS2.0.0RQ.wsdl", 
                array("uri" => $this->config['soap'],
                    "location" => $this->config['soap'],
                    "encoding" => "utf-8",
                    "trace" => true,
                    'cache_wsdl' => WSDL_CACHE_NONE));
        try {
            $client->__soapCall("IgnoreTransactionRQ", 
                    $this->createRequestBody(), 
                    null, 
                    array(Call::createMessageHeader("IgnoreTransactionLLSRQ"), 
                        $this->createSecurityHeader($security)));
        } catch (SoapFault $e) {
            var_dump($e);
        }
    }
    
    private function createSecurityHeader($security) {
        $securityArray = array(
            "BinarySecurityToken" => $security->BinarySecurityToken
        );
        return new \SoapHeader("http://schemas.xmlsoap.org/ws/2002/12/secext", "Security", $securityArray, 1);
    }
    
    private function createRequestBody() {
        $result = array("IgnoreTransactionRQ" => array(
                "_attributes" => array("Version" => $this->config['IgnoreTransactionLLSRQVersion'])
            )
        );
        return $result;
    }
    
}
