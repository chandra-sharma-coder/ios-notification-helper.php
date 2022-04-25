if(!function_exists('ios_notification')){
    function ios_notification($deviceId,$deviceType,$notification,$type){

        $CI = & get_instance();
        $CI->load->database();

        if($deviceType=='ios'){

            if(isset($deviceId) && !empty($deviceId)){

                $deviceToken =$deviceId;
                $passphrase = 'testing';

                $ctx = stream_context_create();

                if($type=="doctor"){
                    $path=APPPATH.'third_party/DadDoctorProduction.pem';
                }else{
                    $path=APPPATH.'third_party/dadPatientPushNotifications.pem';
                }

                stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
                stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                //$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
                $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
              
              if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);
                $body['aps']['alert']=$notification;
                $body['aps']['sound'] = 'default';
                $body['aps']['badge'] = '1';
                $payload = json_encode($body);
                @$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg));
                fclose($fp);
                return $result;
            }
        }
    }
}

                    $DrName=$providerDetails->fName." ".$providerDetails->lName;
                    $PatientName=$userDetails->fName." ".$userDetails->lName;
                    $DateTime=date('Y-m-d', $appointmentData->appointmentFrom)." ".date('G:i', $appointmentData->appointmentFrom);
                    $message = str_replace("DateTime",$DateTime,str_replace("PatientName",$PatientName,str_replace("DrName",$DrName,$this->lang->line('notification_cance_msg'))));
                  
                  $this->common_model->updateData('notifications', array('notification'=>'Cancel Appointment', 'creatorType'=>'provider', 'message'=>$message, 'status'=>0), array('notificationFor'=>$notificationFor, 'appointmentId'=>$this->post('appointmentId')));                  
                  
                  if($providerDetails->notificationSend==0){
                        if($providerDetails->deviceType=='ios'){
                            $notification=ios_notification($userDetails->deviceId,$userDetails->deviceType,$message,$type='patient');
                        }
     
                    }


