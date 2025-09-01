<?php
if(!function_exists('send_wa')){
    function send_wa($target, $pesan){
        $apikey = 'm8UvF2Y7wg7861yXRwQLnc1nxay5qK';
        $sender = '6282268489075';


        $params = [
            'api_key' => $apikey,
            'sender' => $sender,
            'number' => $target,
            'message' => $pesan
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://m2.notifwabiz.com/send-message");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec ($ch);
            curl_close ($ch);

            return $output;
    }
}