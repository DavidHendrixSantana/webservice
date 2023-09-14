<?php

/**
 * @author Arturo Villalpando
 */
class ReadWebService
{
    /**
     * @return array
     */
    public function read($url, $solicitud = "", $method = 'get')
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        if($method != "get") {
            curl_setopt($curl, CURLOPT_POST, true);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //Si content contiene información
        if($solicitud != "") {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $solicitud);
        }
        $json_response = curl_exec($curl);
        // Validate the status
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ( $status != 201 & $status != 200 ) {
            //die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            return array(); // With this we try to avoid break the script if the server return an error...
        }
        // Cerramos la conexión
        curl_close($curl);
        // Regresamos la información
        return json_decode($json_response, true);
    }

    public function readSPE($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($ch);
        
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        
        if($status == 200){
            return $result;
        }else{
            return false;
        }
    }


    public function readEmpleoCom($url, $cookie, $token)
    {
        set_time_limit(3600);

        $headers = array(
            'Content-Type: application/json; charset=UTF-8',
            'Accept: application/json, text/javascript, */*; q=0.01',
            $token,
            'Cookie: ' . $cookie
        );

        $data = array(
            "pageIndex" => 1,
            "pageSize" => "10000",
            "sortExpression" => "PublishDate_Desc"
        );

        $jobs = array();

        //while (true) {

            $ch = curl_init($url);
            //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {
                $json_response = json_decode($response, true);
            $jobs = array_merge($jobs, $json_response['results']);
            } else {
                $message = 'Index: ' . $data['pageIndex'] . ' | ERROR: ' . $httpCode;
                error_log($message, 3, './app/logs/empleocom.log');
                //continue;
            }
        //}
        
        return $jobs;
    }

}