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

// Creamos los controladores
$cC = new CharacterController();
$lC = new LocationController();
$pC = new EpisodeController();

// Creamos los repositorios
$cR = new CharacterRepository();
$lR = new LocationRepository();

?>

<html>

<head>
    <title>Practica</title>
</head>

<body>

    <br>Vamos a traer las localizaciones <br>

    <?php

    // Primero guardamos las localizaciones
    // porque los personajes pueden tener una location_id asociada
    $lC->bajarLocations($lR);

    ?>


    <br>Vamos a traer los episodios <br>

    <?php

    // Después guardamos los episodios
    // para poder relacionarlos luego con los personajes
    $pC->bajarEpisodes();

    ?>


    <br>Vamos a traer los personajes <br>

    <?php

    // Por último guardamos o actualizamos los personajes
    // y relacionamos cada personaje con sus episodios
    $cC->bajarCharacters($cR);

    ?>

</body>

</html>