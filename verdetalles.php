<?php

require_once "repositories/BaseRepository.php";
require_once "repositories/CharacterRepository.php";

$cR = new CharacterRepository();

// Obtenemos el ID enviado desde listado.php por la URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Buscamos el personaje por su ID
$personaje = $id > 0 ? $cR->findById($id) : null;

// Si el personaje existe, buscamos sus episodios relacionados
$episodios = $personaje
    ? $cR->findEpisodesByCharacterId($id)
    : [];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $personaje
            ? htmlspecialchars($personaje['name'])
            : 'Personaje no encontrado' ?>
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #222;
        }

        header {
            background-color: #202020;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .contenedor {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
        }

        .volver {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #202020;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .volver:hover {
            background-color: #444;
        }

        .detalle {
            display: flex;
            gap: 30px;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .detalle img {
            width: 300px;
            max-width: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        .informacion {
            flex: 1;
        }

        .informacion h2 {
            margin-top: 0;
        }

        .informacion p {
            margin: 10px 0;
        }

        .episodios {
            margin-top: 30px;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .episodios h2 {
            margin-top: 0;
        }

        .lista-episodios {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
        }

        .episodio {
            padding: 15px;
            background-color: #f4f4f4;
            border-radius: 7px;
        }

        .episodio h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .mensaje-error {
            background-color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
        }

        @media (max-width: 700px) {
            .detalle {
                flex-direction: column;
            }

            .detalle img {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1>Rick and Morty</h1>
        <p>Detalle del personaje</p>
    </header>

    <main class="contenedor">

        <!-- Permite volver al listado general -->
        <a class="volver" href="listado.php">
            ← Volver al listado
        </a>

        <?php if (!$personaje): ?>

            <!-- Se muestra si el ID no existe en la base de datos -->
            <div class="mensaje-error">
                <h2>Personaje no encontrado</h2>
                <p>El personaje solicitado no existe.</p>
            </div>

        <?php else: ?>

            <!-- INFORMACIÓN DEL PERSONAJE -->
            <section class="detalle">

                <img
                    src="<?= htmlspecialchars($personaje['image'] ?? '') ?>"
                    alt="<?= htmlspecialchars($personaje['name'] ?? 'Personaje') ?>">

                <div class="informacion">

                    <h2>
                        <?= htmlspecialchars($personaje['name'] ?? 'Sin nombre') ?>
                    </h2>

                    <p>
                        <strong>Estado:</strong>
                        <?= htmlspecialchars($personaje['status'] ?? 'Sin información') ?>
                    </p>

                    <p>
                        <strong>Especie:</strong>
                        <?= htmlspecialchars($personaje['species'] ?? 'Sin información') ?>
                    </p>

                    <p>
                        <strong>Tipo:</strong>
                        <?= !empty($personaje['type'])
                            ? htmlspecialchars($personaje['type'])
                            : 'Sin información' ?>
                    </p>

                    <p>
                        <strong>Género:</strong>
                        <?= htmlspecialchars($personaje['gender'] ?? 'Sin información') ?>
                    </p>

                    <p>
                        <strong>Origen:</strong>
                        <?= !empty($personaje['origin'])
                            ? htmlspecialchars($personaje['origin'])
                            : 'Sin información' ?>
                    </p>

                    <p>
                        <strong>Ubicación:</strong>
                        <?= !empty($personaje['location_name'])
                            ? htmlspecialchars($personaje['location_name'])
                            : 'Sin información' ?>
                    </p>

                </div>

            </section>

            <!-- EPISODIOS RELACIONADOS CON EL PERSONAJE -->
            <section class="episodios">

                <h2>Episodios</h2>

                <?php if (empty($episodios)): ?>

                    <p>No hay episodios asociados a este personaje.</p>

                <?php else: ?>

                    <div class="lista-episodios">

                        <?php foreach ($episodios as $episodio): ?>

                            <article class="episodio">

                                <h3>
                                    <?= htmlspecialchars($episodio['name'] ?? 'Sin nombre') ?>
                                </h3>

                                <p>
                                    <strong>Episodio:</strong>
                                    <?= htmlspecialchars($episodio['episode'] ?? 'Sin información') ?>
                                </p>

                                <p>
                                    <strong>Fecha de emisión:</strong>
                                    <?= htmlspecialchars($episodio['air_date'] ?? 'Sin información') ?>
                                </p>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

        <?php endif; ?>

    </main>

</body>

</html>