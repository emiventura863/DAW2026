<?php

class LocationController extends ApiController {

    private $cUrl;

    public function __construct() {

        $this->cUrl = curl_init("https://rickandmortyapi.com/api/location");

        curl_setopt($this->cUrl, CURLOPT_RETURNTRANSFER, true);
    }

    public function getCUrl() {
        return $this->cUrl;
    }

    public function setCUrl($cUrl): void {
        $this->cUrl = $cUrl;
    }

    #[\Override]
    public function info(\CurlHandle $cUrl) {
        return parent::info($cUrl);
    }

    public function bajarLocations() {

        $pages = $this->info($this->getCUrl())["pages"];

        for ($i = 1; $i <= $pages; $i++) {

            $url = "https://rickandmortyapi.com/api/location/?page=" . $i;

            $this->setCUrl(curl_init($url));

            curl_setopt($this->cUrl, CURLOPT_RETURNTRANSFER, true);

            $respuesta = curl_exec($this->cUrl);

            $httpCode = curl_getinfo($this->cUrl, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {

                $locations = json_decode($respuesta, true);

                foreach ($locations["results"] as $location) {

                    echo "<br>" . "Pagina: " . $i . " ID: " . $location["id"] . " | Nombre: " . $location["name"] . " Tipo: " . $location["type"] . " Dimension: " . $location["dimension"] . "<br>";
                }
            }
            curl_close($this->cUrl);
        }
    }
}
