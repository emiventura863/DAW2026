<?php

require "controllers/ApiController.php";
require "controllers/CharacterController.php";
require "controllers/LocationController.php";
require "controllers/EpisodeController.php";
require "repositories/BaseRepository.php";
require "repositories/CharacterRepository.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$cC = new CharacterController();
$lC = new LocationController();
$pC = new EpisodeController();

?>
<html>
    <head>
        <title>Practica</title>
    </head>
    <body>
        
        Vamos a traer los personajes <br>
        
            <?php
            
            $conexion = new CharacterRepository();
            
            $conexion->prueba();
            
            
            $info = $cC->info($cC->getCUrl());
            
            $cC->bajarCharacters();
            
            ?>
        
        <br>Vamos a traer las localizaciones <br>
            
            <?php
            
            $lC->bajarLocations();
            
            ?>
            
        <br>Vamos a traer los episodios <br>
        
        <?php
        
        $pC->bajarEpisodes();
        
        ?>
        
    </body>
</html>


