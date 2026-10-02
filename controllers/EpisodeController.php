<?php

require_once __DIR__ . "/ApiController.php";
require_once __DIR__ . "/../repositories/EpisodeRepository.php";

class EpisodeController extends ApiController
{
    private $cUrl;
    private EpisodeRepository $episodeRepository;

    public function __construct()
    {
        // Inicializa la conexión con la API de episodios
        $this->cUrl = curl_init("https://rickandmortyapi.com/api/episode");
        curl_setopt($this->cUrl, CURLOPT_RETURNTRANSFER, true);

        // Repositorio encargado de guardar los episodios en la base de datos
        $this->episodeRepository = new EpisodeRepository();
    }

    public function getCUrl()
    {
        return $this->cUrl;
    }

    public function setCUrl($cUrl): void
    {
        $this->cUrl = $cUrl;
    }

    // Obtiene la información general de la API, incluida la cantidad de páginas
    #[\Override]
    public function info(\CurlHandle $cUrl)
    {
        return parent::info($cUrl);
    }

    // Recorre todas las páginas de episodios y los guarda en la base de datos
    public function bajarEpisodes(): void
    {
        // Obtenemos la cantidad total de páginas
        $pages = $this->info($this->getCUrl())["pages"];

        for ($i = 1; $i <= $pages; $i++) {

            // Armamos la URL correspondiente a cada página
            $url = "https://rickandmortyapi.com/api/episode/?page=" . $i;

            $this->setCUrl(curl_init($url));

            curl_setopt($this->cUrl, CURLOPT_RETURNTRANSFER, true);

            // Ejecutamos la consulta a la API
            $respuesta = curl_exec($this->cUrl);

            $httpCode = curl_getinfo(
                $this->cUrl,
                CURLINFO_HTTP_CODE
            );

            // Si la API respondió correctamente, procesamos los episodios
            if ($httpCode == 200) {

                $episodes = json_decode($respuesta, true);

                foreach ($episodes["results"] as $episode) {

                    // Guardamos o actualizamos cada episodio en la base de datos
                    $this->episodeRepository->create([
                        "id" => $episode["id"],
                        "name" => $episode["name"],
                        "air_date" => $episode["air_date"],
                        "episode" => $episode["episode"],
                        "created" => $episode["created"]
                    ]);
                }
            }

            // Cerramos la conexión CURL de esta página
            curl_close($this->cUrl);
        }
    }
}
