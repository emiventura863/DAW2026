<?php

class CharacterController extends ApiController {

    private $cUrl;

    public function __construct() {

        $this->cUrl = curl_init("https://rickandmortyapi.com/api/character");

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

    public function bajarCharacters() {

        $pages = $this->info($this->getCUrl())["pages"];

        for ($i = 1; $i <= $pages; $i++) {

            $url = "https://rickandmortyapi.com/api/character/?page=" . $i;

            $newCurl = curl_init($url);

            curl_setopt($newCurl, CURLOPT_RETURNTRANSFER, true);

            $respuesta = curl_exec($newCurl);

            $httpCode = curl_getinfo($newCurl, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {

                $characters = json_decode($respuesta, true);

                foreach ($characters["results"] as $character) {
                    
                    echo "<br> " . "Pagina: " . $i . " - " . "ID: " . $character["id"] . " | " . $character["name"] . " - Especie: " . $character["species"] . "- Genero: " . $character["gender"] . "<br>";
                } 
            }
        }
    }
}
