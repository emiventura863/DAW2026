<?php

class EpisodeController extends ApiController{
    
    private $cUrl;

    public function __construct() {

        $this->cUrl = curl_init("https://rickandmortyapi.com/api/episode");

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
    
    
    public function bajarEpisodes(){
        
        $pages = $this->info($this->getCUrl())["pages"];
        
        for($i = 1; $i <= $pages; $i++){
            
            $url = "https://rickandmortyapi.com/api/episode/?page=" . $i;
            
            $newCurl = curl_init($url);
            
            curl_setopt($newCurl, CURLOPT_RETURNTRANSFER, true);
            
            $respuesta = curl_exec($newCurl);
            
            $httpCode = curl_getinfo($newCurl,CURLINFO_HTTP_CODE);
            
            if($httpCode == 200){
                
                $episodes = json_decode($respuesta,true);
                
                foreach($episodes["results"] as $episode ){
                    
                    echo "<br>"."Pagina: ".$i." ID: ".$episode["id"]." | Nombre: ".$episode["name"]." Lanzamiento: ".$episode["air_date"]." Episodio: ".$episode["episode"]."<br>";
                    
                } 
                
            }
            
        }
            
        
        
    }
    
    
    
}
