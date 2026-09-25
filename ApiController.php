<?php

abstract class ApiController {

    public function info(CurlHandle $cUrl) {

        $respuesta = curl_exec($cUrl);

        $httpCode = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {

            $info = json_decode($respuesta, true);
        }

        $array = ["count" => $info["info"]["count"],
            "pages" => $info["info"]["pages"]];

        return $array;
    }
}
