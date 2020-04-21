<?php
namespace Up\Sabre\Rest\Api;
use Up\Sabre\Rest\Call;
use Up\Sabre\Config;

class BargainFinderMax{
    
    public $limit;
    public $offset;
    public $urlParmater = '';

    public function __construct(Array $params)
    {
       $this->params = $params;
        $this->config = Config::get()[Config::get()['env']];
        // dd($this->config);
        $this->offset = $params['offset'];
        $this->limit = $params['limit'];

        if(!empty($this->limit)){
           $this->urlParmater .= '&limit='.$this->limit;
        }

        if(!empty($this->offset)){
          $this->urlParmater .= '&offset='.$this->offset;
       }

       $this->path = '/v1/offers/shop?enabletagging=true'.$this->urlParmater;
        if(!$this->validateParams()){
          throw new \Exception("Error Processing Request. Required parameter not found!", 1);
          
        }

    }
    
    public function run()
    {
        $Call = new Call();
        $result = $Call->executePostCall($this->path, $this->getRequest());
        return $result;
    }

    public function validateParams()
    {
      if (empty($this->params)) {
        return false;
      }
      if (empty($this->params['OriginDestinationInformation']) || empty($this->params['PassengerTypeQuantity'])) {
        return false;
      }

      return true;
    }


    private function getRequest() {
        $request = '
          {
            "OTA_AirLowFareSearchRQ": {
              "OriginDestinationInformation": [';
              foreach ($this->params['OriginDestinationInformation'] as $key => $value) {
                $request .= '{
                  "DepartureDateTime": "'.$value['DepartureDateTime'].'T00:00:00",
                  "DestinationLocation": {
                    "LocationCode": "'.$value['DestinationLocation'].'"
                  },
                  "OriginLocation": {
                    "LocationCode": "'.$value['OriginLocation'].'"
                  },
                  "RPH": "'.$key.'"
                }';
                if($key < count($this->params['OriginDestinationInformation'])-1){ $request .= ','; }
              }
                 

              $request .= '],
              "POS": {
                "Source": [
                  {
                    "PseudoCityCode": "'.$this->config['group'].'",
                    "RequestorID": {
                      "CompanyName": {
                        "Code": "TN"
                      },
                      "ID": "1",
                      "Type": "1"
                    }
                  }
                ]
              },
              "TPA_Extensions": {
                "IntelliSellTransaction": {
                  "RequestType": {
                    "Name": "200ITINS"
                  }
                }
              },
              "TravelPreferences": {
                "TPA_Extensions": {
                  "DataSources": {
                    "ATPCO": "Enable",
                    "LCC": "Disable",
                    "NDC": "Disable"
                  },
                  "NumTrips": {}
                }
              },
              "TravelerInfoSummary": {
                "AirTravelerAvail": [
                  {
                    "PassengerTypeQuantity": [';

                    foreach ($this->params['PassengerTypeQuantity'] as $key => $value) {

                      $request .= '{
                        "Code": "'.$value['Code'].'",
                        "Quantity": '.$value['Quantity'].'
                      }';
                      if($key < count($this->params['PassengerTypeQuantity'])-1){ $request .= ','; }
                    }
                    $request .= ']
                  }
                ],
                "SeatsRequested": [
                  '.$this->params['SeatsRequested'].'
                ]
              },
              "Version": "1"
            }
          }';
        return $request;
    }
}