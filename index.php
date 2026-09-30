<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "controllers/ApiController.php";
require_once "controllers/CharacterController.php";
require_once "controllers/LocationController.php";
require_once "controllers/EpisodeController.php";
require_once "repositories/BaseRepository.php";
require_once "repositories/CharacterRepository.php";
require_once "repositories/LocationRepository.php";

$cC = new CharacterController();
$lC = new LocationController();
$pC = new EpisodeController();

$cR = new CharacterRepository();

?>
<html>
    <head>
        <title>Practica</title>
    </head>
    <body>
        
        Vamos a traer los personajes <br>
        
            <?php
            
            $info = $cC->info($cC->getCUrl());
            
            $cC->bajarCharacters($cR);
            
            $cC->traerCharacter($cR, 213);
            
            
            ?>
        
        <br>Vamos a traer las localizaciones <br>
            
            <?php
            $lR = new LocationRepository();
            $lC->bajarLocations($lR);
            ?>
            
        <br>Vamos a traer los episodios <br>
        
        <?php
        // $pC->bajarEpisodes();
        ?>
        
    </body>
</html>


