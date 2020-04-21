<?php
namespace Up\Sabre\Rest\Api;
use Up\Sabre\Rest\Call;
use Up\Sabre\Config;

class CreatePassengerNameRecord{

    private $config;

    public function __construct($params)
    {
        // dd($params);
        $this->config = Config::get()[Config::get()['env']];
        $this->path = '/v2.3.0/passenger/records?mode=create';
        $this->params = $params;
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
    //   if (empty($this->params)) {
    //     return false;
    //   }
    //   if (empty($this->params['OriginDestinationInformation']) || empty($this->params['PassengerTypeQuantity'])) {
    //     return false;
    //   }

      return true;
    }


    private function getRequest() {

      $personName = [];
      foreach($this->params['passangerInfo'] as $key => $p){

        if($p['type'] == 'ADT'){
          $personName[] = '{
            "NameNumber": "'.($key+1).'.1",
            "PassengerType": "'.$p['type'].'",
            "GivenName": "'.$p['info']->first_name.'",
            "Surname": "'.$p['info']->last_name.'"
          }';
        }

        elseif($p['type'] == 'CNN'){
          $personName[] = '{
            "NameNumber": "'.($key+1).'.1",
            "Infant": false,
            "NameReference": "C04",
            "PassengerType": "'.$p['type'].'",
            "GivenName": "'.$p['info']->first_name.'",
            "Surname": "'.$p['info']->last_name.'"
          }';

        }

        elseif($p['type'] == 'INF'){
          $personName[] = '{
            "NameNumber": "'.($key+1).'.1",
            "Infant": false,
            "PassengerType": "'.$p['type'].'",
            "GivenName": "'.$p['info']->first_name.'",
            "Surname": "'.$p['info']->last_name.'"
          }';

        }

      }


      $personNameWithoutInfant = [];
      foreach($this->params['passangerInfo'] as $key => $p){
        if($p['type'] != 'INF'){
          $personNameWithoutInfant[]=' {
            "SegmentNumber": "A",
            "PersonName": {
              "DateOfBirth": "'.(date('Y-m-d', strtotime($p['info']->dob))).'",
              "Gender": "M",
              "NameNumber": "'.($key+1).'.1",
              "GivenName": "'.$p['info']->first_name.'",
              "Surname": "'.$p['info']->last_name.'"
            },
            "VendorPrefs": {
              "Airline": {
                "Hosted": false
              }
            }
          }';
      
        }
      }

      $service = [];
      
      foreach($this->params['passangerInfo'] as $key => $p){
        if($p['type'] == 'INF'){
          $service[] ='
            {
              "PersonName": {
                  "NameNumber": "'.$key.'.1"
              },
              "SSR_Code": "INFT",
              "Text": "'.$p['info']->last_name.'/'.$p['info']->first_name.'/'.(date('jMy', strtotime($p['info']->dob))).'"
            }
          ';      
        }
      }


      $flightSegment = [];
      foreach($this->params['flights'] as $key1 => $flight){
        foreach($flight['scheduleDesc'] as $key2 => $schedule){
          $date = $flight['departure_date'];
          if(isset($flight['schedules'][$key2]['departureDateAdjustment'])){
            $date = date('Y-m-d', strtotime($date. ' + '.$flight['schedules'][$key2]['departureDateAdjustment'].' days'));
          }
          
          $flightSegment[] ='
            {
              "ArrivalDateTime": "'.$date.'T'.date("H:i:s", strtotime(substr($schedule['arrival']['time'], 0, 8))).'",
              "DepartureDateTime": "'.$date.'T'.date("H:i:s", strtotime(substr($schedule['departure']['time'], 0, 8))).'",
              "FlightNumber": "'.$schedule['carrier']['marketingFlightNumber'].'",
              "NumberInParty": "'.count($personName).'",
              "ResBookDesigCode": "Y",
              "Status": "NN",
              "DestinationLocation": {
                "LocationCode": "'.$schedule['arrival']['airport'].'"
              },
              "MarketingAirline": {
                "Code": "'.$schedule['carrier']['marketing'].'",
                "FlightNumber": "'.$schedule['carrier']['marketingFlightNumber'].'"
              },
              "OriginLocation": {
                "LocationCode": "'.$schedule['departure']['airport'].'"
              }
            }';
          }
        }

        $passengerType = [];
        foreach($this->params['passangers'] as $key => $p){
          if($p > 0){
            $passengerType[] ='{
              "Code": "'.$key.'",
              "Quantity": "'.$p.'"
            }';    
          }    
        }

        $request = '
        {
            "CreatePassengerNameRecordRQ": {
              "version": "2.3.0",
              "targetCity": "8F1J",
              "haltOnAirPriceError": false,



              "TravelItineraryAddInfo": {
                "AgencyInfo": {
                  "Address": {
                    "AddressLine": "SABRE TRAVEL",
                    "CityName": "SOUTHLAKE",
                    "CountryCode": "US",
                    "PostalCode": "76092",
                    "StateCountyProv": {
                      "StateCode": "TX"
                    },
                    "StreetNmbr": "3150 SABRE DRIVE"
                  },
                  "Ticketing": {
                    "TicketType": "7TAW"
                  }
                },
                "CustomerInfo": {
                  "ContactNumbers": {
                    "ContactNumber": [
                      {
                        "NameNumber": "1.1",
                        "Phone": "817-555-1212",
                        "PhoneUseType": "H"
                      }
                    ]
                  },

                  "PersonName": [';
                    $request .= implode(',', $personName);
                  $request .=']
                }
              },
    

              "AirBook": {
                "HaltOnStatus": [
                  {
                    "Code": "HL"
                  },
                  {
                    "Code": "KK"
                  },
                  {
                    "Code": "LL"
                  },
                  {
                    "Code": "NN"
                  },
                  {
                    "Code": "NO"
                  },
                  {
                    "Code": "UC"
                  },
                  {
                    "Code": "US"
                  }
                ],
                "OriginDestinationInformation": {
                  "FlightSegment": [';
                  $request .= implode(',', $flightSegment);
                  $request .= ']
                },
                "RedisplayReservation": {
                  "NumAttempts": 10,
                  "WaitInterval": 300
                }
              },



              "AirPrice": [
                {
                  "PriceRequestInformation": {
                    "Retain": true,
                    "OptionalQualifiers": {
                      "FOP_Qualifiers": {
                        "BasicFOP": {
                          "Type": "CK"
                        }
                      },
                      "PricingQualifiers": {
                        "PassengerType": [';
                        $request .= implode(',', $passengerType);
                        $request .=']
                      }
                    }
                  }
                }
              ],




               "SpecialReqDetails": {
                "AddRemark": {
                  "RemarkInfo": {
                    "FOP_Remark": {
                      "Type": "CHECK"
                    }
                  }
                },
                "SpecialService": {
                   "SpecialServiceInfo": {

                      "SecureFlight": [
                        {   
                        "PersonName": 
                        {
                          "DateOfBirth": "1993-10-07",
                          "Gender": "F",
                          "NameNumber": "1.1",
                          "GivenName": "Md",
                          "Surname": "Hassan"
                        } ,
                        "SegmentNumber": "A"    
                      }],
                      

                      "SecureFlight": [
                          {   
                          "PersonName": 
                            {
                              "DateOfBirth": "2009-05-14",
                              "Gender": "F",
                              "NameNumber": "2.1",
                              "GivenName": "mim",
                              "Surname": "khan"
                            } ,
                            "SegmentNumber": "A"    
                        }],

                      "Service": [
                      {
                        "PersonName": 
                        {
                          "NameNumber": "2.1"
                        },
                        "SSR_Code": "CHLD",
                        "Text": "01MAY07"

                      }]
                   }
                }
              },
              
              
              "PostProcessing": {
                
                "EndTransaction": {
                  "Source": {
                    "ReceivedFrom": "SP WEB"
                  }
                },
                "RedisplayReservation": {
                  "waitInterval": 100
                }
              }
            }
          }
        ';
        return $request;
    }
}