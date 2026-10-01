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
                    $image = $character["image"] ?? null; // <-- Asegurado al final
                    
                    // Validacion, existe en la bd?
                    
                    if ($cR->find($id)) {

                        echo "El character ya existe en la base de datos";
                        
                    } else {

                        try {

                            // Respetando el orden del constructor: id, name, status, species, type, gender, origin, location_id, episode, image
                            $cs = new Character($id, $name, $status, $species, $type, $gender, $origin, $location_id, $episode, $image);
                            $cR->create($cs);

                            echo "Guardado exitosamente";
                        } catch (Exception $ex) {

                            echo $ex->getMessage();
                        }
                    }
                }
            }

            curl_close($this->cUrl);
        }
    }

    function traerCharacter(CharacterRepository $cR, int $id) {

        $char = new Character();
        
        // Validacion, existe en la bd?
        
        if ($cR->find($id)) {

            $arrayChar = $cR->find($id);
            
            $char->setId($arrayChar["id"]);
            $char->setName($arrayChar["name"]);
            $char->setStatus($arrayChar["status"]);
            $char->setSpecies($arrayChar["species"]);
            $char->setType($arrayChar["type"]);
            $char->setGender($arrayChar["gender"]);
            $char->setOrigin($arrayChar["origin"]);
            $char->setLocation_id($arrayChar["location_id"]);
            $char->setEpisode($arrayChar["episode"]);
            $char->setImage($arrayChar["image"]); // <-- ¡Agregado para que no falte la imagen!

            echo $char;
            
        }else{
            
            echo "El personaje no esta en la base de datos";
            
        }
    }
}