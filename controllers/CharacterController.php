<?php

require_once "repositories/BaseRepository.php"; 
require_once "repositories/CharacterRepository.php";
require_once "classes/Character.php";

class CharacterController extends ApiController {

    private $cUrl;

    public function __construct() {

        $this->cUrl = curl_init("https://rickandmortyapi.com/api/character/");
        
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

    public function bajarCharacters(CharacterRepository $cR) {

        $pages = $this->info($this->getCUrl())["pages"];

        for ($i = 1; $i <= $pages; $i++) {

            $url = "https://rickandmortyapi.com/api/character/?page=" . $i;
            
            $this->setCUrl(curl_init($url));
            
            curl_setopt($this->cUrl, CURLOPT_RETURNTRANSFER, true);
            
            $respuesta = curl_exec($this->cUrl);

            $httpCode = curl_getinfo($this->cUrl, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {

                $characters = json_decode($respuesta, true);

                foreach ($characters["results"] as $character) {

                    echo "<br> " . "Pagina: " . $i . " - " . "ID: " . $character["id"] . " | " . $character["name"] . " - Especie: " . $character["species"] . "- Genero: " . $character["gender"] . "<br>";

                    $id = $character["id"];
                    $name = $character["name"];
                    $status = $character["status"];
                    $species = $character["species"];
                    $type = $character["type"];
                    $gender = $character["gender"];
                    $origin = null;
                    $location_id = null;
                    $episode = null;

                    try {

                        $cs = new Character($id, $name, $status, $species, $type, $gender, $origin, $location_id, $episode);
                        $cR->create($cs);
                        echo "Guardado exitosamente";
                    } catch (Exception $ex) {

                        echo $ex->getMessage();
                    }
                }
            }
            
            curl_close($this->cUrl);
        }
    }
}
