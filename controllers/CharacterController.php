<?php

require_once "repositories/BaseRepository.php";
require_once "repositories/CharacterRepository.php";
require_once "classes/Character.php";

class CharacterController extends ApiController
{

    private $cUrl;

    public function __construct()
    {

        $this->cUrl = curl_init("https://rickandmortyapi.com/api/character/");

        curl_setopt($this->cUrl, CURLOPT_RETURNTRANSFER, true);
    }

    public function getCUrl()
    {

        return $this->cUrl;
    }

    public function setCUrl($cUrl): void
    {
        $this->cUrl = $cUrl;
    }

    #[\Override]
    public function info(\CurlHandle $cUrl)
    {
        return parent::info($cUrl);
    }

    // Descarga todos los personajes de la API y los guarda o actualiza en la base de datos
    public function bajarCharacters(CharacterRepository $cR)
    {
        // Obtenemos la cantidad total de páginas de personajes
        $pages = $this->info($this->getCUrl())["pages"];

        // Recorremos todas las páginas de la API
        for ($i = 1; $i <= $pages; $i++) {

            $url = "https://rickandmortyapi.com/api/character/?page=" . $i;

            $this->setCUrl(curl_init($url));

            curl_setopt($this->cUrl, CURLOPT_RETURNTRANSFER, true);

            // Ejecutamos la consulta a la API
            $respuesta = curl_exec($this->cUrl);

            $httpCode = curl_getinfo(
                $this->cUrl,
                CURLINFO_HTTP_CODE
            );

            // Procesamos la respuesta solamente si fue correcta
            if ($httpCode == 200) {

                $characters = json_decode($respuesta, true);

                // Recorremos todos los personajes de la página actual
                foreach ($characters["results"] as $character) {

                    echo "<br>
                    Pagina: " . $i .
                        " - ID: " . $character["id"] .
                        " | " . $character["name"] .
                        " - Especie: " . $character["species"] .
                        " - Genero: " . $character["gender"] .
                        "<br>";

                    // Datos principales del personaje obtenidos desde la API
                    $id = $character["id"];
                    $name = $character["name"];
                    $status = $character["status"];
                    $species = $character["species"];
                    $type = $character["type"];
                    $gender = $character["gender"];

                    // Guardamos el nombre del lugar de origen
                    $origin = $character["origin"]["name"] ?? null;

                    // Obtenemos el ID de la ubicación actual desde su URL
                    $location_id = null;

                    if (!empty($character["location"]["url"])) {
                        $location_id = (int) basename(
                            $character["location"]["url"]
                        );
                    }

                    // Guardamos temporalmente la lista de episodios
                    $episode = $character["episode"] ?? [];

                    // Imagen del personaje
                    $image = $character["image"] ?? null;

                    try {

                        // Creamos el objeto Character con los datos obtenidos
                        $cs = new Character(
                            $id,
                            $name,
                            $status,
                            $species,
                            $type,
                            $gender,
                            $origin,
                            $location_id,
                            $episode,
                            $image
                        );

                        // Si el personaje no existe lo inserta.
                        // Si ya existe, actualiza sus datos.
                        $cR->create($cs);

                        // Recorremos los episodios en los que aparece
                        foreach ($episode as $episodeUrl) {

                            // Obtenemos solamente el ID desde la URL del episodio
                            $episodeId = (int) basename($episodeUrl);

                            // Guardamos la relación personaje-episodio
                            if ($episodeId > 0) {
                                $cR->addEpisode($id, $episodeId);
                            }
                        }

                        echo "Guardado/actualizado exitosamente";
                    } catch (Exception $ex) {

                        echo $ex->getMessage();
                    }
                }
            }

            // Cerramos la conexión CURL de la página actual
            curl_close($this->cUrl);
        }
    }

    function traerCharacter(CharacterRepository $cR, int $id)
    {

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
        } else {

            echo "El personaje no esta en la base de datos";
        }
    }
}
