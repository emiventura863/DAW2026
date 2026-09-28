<?php

abstract class ApiController {

    public function info(CurlHandle $cUrl) {

        $respuesta = curl_exec($cUrl);
        $httpCode = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);

       
        $array = [
            "count" => 0,
            "pages" => 0
        ];

        if ($httpCode == 200 && $respuesta !== false) {
            $info = json_decode($respuesta, true);

    
            if (isset($info["info"])) {
                $array["count"] = $info["info"]["count"];
                $array["pages"] = $info["info"]["pages"];
            }
        }

        return $array;
    }
}