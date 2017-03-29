<?php

class CC
{
    var $apiKey;
    var $token;

    public function __construct($apiKey,$token)
    {
        $this->apiKey = $apiKey;
        $this->token = $token;
    }

    public function subscribe($listId,$data,$doubleOptin)
    {              
      $confirmed = $doubleOptin ? 'false' : 'true';              
      $url = "https://api.constantcontact.com/v2/contacts?email=".$data['email']."&status=ALL&limit=50&api_key=".$this->apiKey;
      
      $header[] = "Authorization: Bearer ".$this->token;
      $header[] = 'Content-Type: application/json';
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
      $response  = json_decode(curl_exec($ch));    
      $error = curl_error($ch);  
           
      curl_close($ch); 
      
      if($error){            
        $api['error'] = $error;
      }elseif($response->results){
        $api['error'] = "You are already subscribed";
      }elseif(is_array($response) && isset($response[0]) && $response[0]->error_message){
        $api['error'] = $response[0]->error_message;        
      }else{
        $url = "https://api.constantcontact.com/v2/contacts?action_by=ACTION_BY_VISITOR&api_key=".$this->apiKey;
        
        $body = '{
          	"lists": [
          		{
          		"id": "'.$listId.'"
          		}
          	],
          	  "confirmed": true,
          	  "email_addresses": [
          		{
          		"email_address": "'.$data['email'].'"
          		}
          	],
            "first_name": "'.$data['firstName'].'",
            "last_name": "'.$data['lastName'].'"
          }';
          
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);          
          $response  = json_decode(curl_exec($ch));
          
          if(curl_error($ch)){
            $api['error'] = curl_error($ch);
          }            
          curl_close($ch);           
          
          if(is_array($response) && isset($response[0]) && $response[0]->error_message){
            $api['error'] = $response[0]->error_message;
          }           
      }
      return $api;    
    }


}
