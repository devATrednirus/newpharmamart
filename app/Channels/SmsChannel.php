<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class SmsChannel
{
     

    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {

        $limit_text = true;
        try {
            $to = $notification->getTo($notifiable);

            $sms_text = $notification->toSms($notifiable);
            // dd($limit_text);
            if(is_array($sms_text)){
                $sms_array = $sms_text;
                $limit_text =  $sms_array['limit_text'];
                $sms_text =  $sms_array['sms_text'];
            }

            
            if($limit_text){

                $sms_text= substr($sms_text,0,157);//."...";
            }
            
            $sms_text=rawurlencode($sms_text);
 
	       $response = $this->textlocal($to,$sms_text);
        	
        	return $response;
           
         
        } catch (\Exception $exception) {
            
            \Log::error($exception->getMessage());
            return ;
           
        }
    }

    public function global91sms($contacts,$sms_text){

        $routeid = '459'; 
        $api_key = "";
        $from= "RDNIRS";
        
        $api_url = "http://www.global91sms.in/app/smsapi/index.php?key=".$api_key."&entity=1701160597365318576&tempid=999999999999999&routeid=".$routeid."&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text;

        return  $response = file_get_contents( $api_url);

    }


    public function textlocal($numbers,$message){

        // Account details
        $apiKey = urlencode('NTA0YjcxNzg3NTUyNjU1NTU4NmU1YTc2Mzg0ZTMwNTc=');
    
        $sender = urlencode('RDNIRS');
        
        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        
        curl_close($ch);
        
        return  $response;

    }
 
}
