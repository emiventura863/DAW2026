<?php

require "ApiController.php";
require "CharacterController.php";
require "LocationController.php";
require "EpisodeController.php";

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


