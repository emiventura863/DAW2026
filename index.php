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
            
            $cR = new CharacterRepository();
            
            $info = $cC->info($cC->getCUrl());
            
            $cC->bajarCharacters($cR);
            
            ?>
        
        <br>Vamos a traer las localizaciones <br>
            
            <?php
            
            //$lC->bajarLocations();
            
            ?>
            
        <br>Vamos a traer los episodios <br>
        
        <?php
        
        //$pC->bajarEpisodes();
        
        ?>
        
    </body>
</html>


