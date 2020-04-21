<?php

namespace Up\Sabre\Soap;
use Up\Sabre\Config;

class Call {

    private $lastInFlow = false;
    private $actionName;

    public function __construct($actionName) {
        $this->actionName = $actionName;
    }

    public function doCall($request) {
        $securityCall = new Auth();
        $securityToken = $securityCall->executeRequest();
        $sacsClient = new SACSClient();
        $result = $sacsClient->doCall(Call::getMessageHeaderXml($this->actionName) . $this->createSecurityHeader($securityToken), $request, $this->actionName);
        if ($this->lastInFlow) {
            error_log("Ignore and close");
            $this->ignoreAndCloseSession($securityToken);
        }
        return $result;
    }

    private function ignoreAndCloseSession($security) {
        $it = new IgnoreTransactionRequest();
        $it->executeRequest($security);
        $sc = new SessionCloseRequest();
        $sc->executeRequest($security);
    }

    private function createSecurityHeader($securityToken) {
        $security = array("Security" => array(
                "_namespace" => "http://schemas.xmlsoap.org/ws/2002/12/secext",
                "BinarySecurityToken" => array(
                    "_attributes" => array("EncodingType" => "Base64Binary", "valueType" => "String"),
                    "_value" => $securityToken->BinarySecurityToken
                )
            )
        );
        return XMLSerializer::generateValidXmlFromArray($security);
    }

    public static function createMessageHeader($actionString) {
        $messageHeaderXml = Call::getMessageHeaderXml($actionString);
        $soapVar = new \SoapVar($messageHeaderXml, XSD_ANYXML, null, null, null);
        return new \SoapHeader("http://www.ebxml.org/namespaces/messageHeader", "MessageHeader", $soapVar, 1);
    }

    private static function getMessageHeaderXml($actionString) {
        
        $messageHeader = array("MessageHeader" => array(
                "_namespace" => "http://www.ebxml.org/namespaces/messageHeader",
                "From" => array("PartyId" => "sample.url.of.sabre.client.com"),
                "To" => array("PartyId" => "webservices.sabre.com"),
                "CPAId" => config()['test']['group'],
                "ConversationId" => "euholidy",
                "Service" => $actionString,
                "Action" => $actionString,
                "MessageData" => array(
                    "MessageId" => "euholidy112",
                    "Timestamp" =>  "2020-04-20T09:25:27Z",
                    "TimeToLive" => "2020-04-20T09:25:27Z"
                )
            )
        );
        return XMLSerializer::generateValidXmlFromArray($messageHeader);
    }

    public function setLastInFlow($lastInFlow) {
        $this->lastInFlow = $lastInFlow;
    }

}

class SACSClient {

    function doCall($headersXml, $body, $action) {
        //Data, connection, auth
        $config = Config::get()[Config::get()['env']];
        $soapUrl = $config['soap'];
        
        // xml post structure
        $xml_post_string = '<SOAP-ENV:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">'
                . '<SOAP-ENV:Header>'
                . $headersXml
                . '</SOAP-ENV:Header>'
                . '<SOAP-ENV:Body>'
                . $body
                . '</SOAP-ENV:Body>'
                . '</SOAP-ENV:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: " . $action,
            "Content-length: " . strlen($xml_post_string)
        );

        // Die dump request data
        // xmldd($xml_post_string);

        error_log($action);
        error_log($xml_post_string);
        error_log("------------------------------------------------");

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, false);

        // converting
        $response = curl_exec($ch);
        curl_close($ch);
        // dd($response);

        // Die dump response data

        return $response;
    }

}
