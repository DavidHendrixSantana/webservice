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
        
        $result = curl_exec($ch);
        
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        
        if($status == 200){
            return $result;
        }
    }
}