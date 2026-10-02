<?php

require_once "repositories/BaseRepository.php";
require_once "repositories/CharacterRepository.php";

// Creamos el objeto del repositorio de personajes
$cR = new CharacterRepository();

// CONFIGURACIÓN DE LA PAGINACIÓN

// Cantidad de personajes que queremos mostrar por página
$porPagina = 60;


// PÁGINA ACTUAL
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

// Evitamos que alguien ponga ?page=0 o un número negativo
if ($page < 1) {
    $page = 1;
}


// BUSCADOR
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// FILTRO POR ESTADO
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

// Filtro por especie
$species = isset($_GET['species']) ? trim($_GET['species']) : '';

// Filtro por género
$gender = isset($_GET['gender']) ? trim($_GET['gender']) : '';

// CANTIDAD TOTAL DE RESULTADOS
$total = $cR->countAll($search, $status, $species, $gender);

// CANTIDAD DE PÁGINAS
$totalPaginas = (int) ceil($total / $porPagina);

if ($totalPaginas > 0 && $page > $totalPaginas) {
    $page = $totalPaginas;
}

// OBTENER LOS PERSONAJES
$personajes = $cR->findAll(
    $page,
    $porPagina,
    $search,
    $status,
    $species,
    $gender
);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Personajes - Rick and Morty</title>

    <style>
        /* ESTADO DEL PERSONAJE */

        .estado {
            display: flex;
            align-items: center;
            gap: 7px;
            font-weight: bold;
        }

        .punto-estado {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .estado-alive .punto-estado {
            background-color: #28a745;
        }

        .estado-dead .punto-estado {
            background-color: #dc3545;
        }

        .estado-unknown .punto-estado {
            background-color: #6c757d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        header {
            background-color: #202020;
            color: white;
            padding: 25px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        .contenedor {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .info {
            text-align: center;
            margin-bottom: 25px;
            color: #555;
        }

        .buscador {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .buscador input,
        .buscador select {
            padding: 10px;
            font-size: 15px;
        }

        .buscador button {
            padding: 10px 18px;
            cursor: pointer;
        }

        .personajes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .tarjeta {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .tarjeta:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        .tarjeta img {
            width: 100%;
            display: block;
        }

        /* DATOS DE LA TARJETA */
        .datos {
            padding: 18px;
        }

        .datos h2 {
            margin: 0 0 15px 0;
            font-size: 21px;
            color: #222;
        }

        .datos p {
            margin: 8px 0;
            color: #666;
            font-size: 14px;
        }

        .datos strong {
            color: #333;
        }

        .paginacion {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }

        .paginacion a {
            text-decoration: none;
            background-color: #202020;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
        }

        .paginacion a:hover {
            background-color: #444;
        }

        .paginacion .pagina-activa {
            background-color: #28a745;
            font-weight: bold;
        }

        .btn-detalle {
            display: inline-block;
            margin-top: 12px;
            padding: 9px 14px;
            background-color: #202020;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-detalle:hover {
            background-color: #444;
        }
    </style>

</head>

<body>

    <header>

        <h1>Rick and Morty</h1>

        <p>Personajes</p>

    </header>


    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenedor">

        <!-- BUSCADOR + FILTRO -->
        <form method="GET" action="listado.php" class="buscador">


            <!-- BUSCAR POR NOMBRE -->
            <input
                type="text"
                name="search"
                placeholder="Buscar personaje..."
                value="<?= htmlspecialchars($search) ?>">


            <!-- FILTRAR POR ESTADO -->
            <select name="status">

                <!--
                    value="" significa que no queremos
                    filtrar por ningún estado.
                -->
                <option value="">
                    Todos los estados
                </option>


                <!-- ESTADO ALIVE -->
                <option
                    value="Alive"
                    <?= $status === 'Alive' ? 'selected' : '' ?>>
                    Alive
                </option>

                <!-- ESTADO DEAD -->
                <option
                    value="Dead"
                    <?= $status === 'Dead' ? 'selected' : '' ?>>
                    Dead
                </option>

                <!-- ESTADO UNKNOWN -->
                <option
                    value="unknown"
                    <?= $status === 'unknown' ? 'selected' : '' ?>>
                    Unknown
                </option>

            </select>

            <select name="species">
                <option value="">Todas las especies</option>

                <option value="Human"
                    <?= $species === 'Human' ? 'selected' : '' ?>>
                    Human
                </option>

                <option value="Alien"
                    <?= $species === 'Alien' ? 'selected' : '' ?>>
                    Alien
                </option>

                <option value="Humanoid"
                    <?= $species === 'Humanoid' ? 'selected' : '' ?>>
                    Humanoid
                </option>

                <option value="Robot"
                    <?= $species === 'Robot' ? 'selected' : '' ?>>
                    Robot
                </option>

                <option value="Animal"
                    <?= $species === 'Animal' ? 'selected' : '' ?>>
                    Animal
                </option>
            </select>

            <select name="gender">
                <option value="">Todos los géneros</option>

                <option value="Male"
                    <?= $gender === 'Male' ? 'selected' : '' ?>>
                    Male
                </option>

                <option value="Female"
                    <?= $gender === 'Female' ? 'selected' : '' ?>>
                    Female
                </option>

                <option value="Genderless"
                    <?= $gender === 'Genderless' ? 'selected' : '' ?>>
                    Genderless
                </option>

                <option value="unknown"
                    <?= $gender === 'unknown' ? 'selected' : '' ?>>
                    Unknown
                </option>
            </select>


            <!-- BOTÓN -->
            <button type="submit">
                Buscar
            </button>

        </form>

        <!-- INFORMACIÓN DE LA PÁGINA -->
        <p class="info">

            Página <?= $page ?> de <?= $totalPaginas ?>

            —

            <?= $total ?> personajes encontrados

        </p>



        <!-- TARJETAS -->

        <section class="personajes">

            <?php foreach ($personajes as $personaje): ?>

                <?php
                $estado = strtolower($personaje['status'] ?? 'unknown');

                if ($estado === 'alive') {
                    $claseEstado = 'estado-alive';
                } elseif ($estado === 'dead') {
                    $claseEstado = 'estado-dead';
                } else {
                    $claseEstado = 'estado-unknown';
                }
                ?>

                <article class="tarjeta">

                    <!-- IMAGEN DEL PERSONAJE -->
                    <img
                        src="<?= $personaje['image'] ?>"
                        alt="<?= htmlspecialchars($personaje['name']) ?>">

                    <!-- DATOS -->
                    <div class="datos">

                        <!-- NOMBRE -->
                        <h2>
                            <?= htmlspecialchars($personaje['name'] ?? 'Sin nombre') ?>
                        </h2>

                        <!-- STATUS -->
                        <p class="estado <?= $claseEstado ?>">
                            <span class="punto-estado"></span>
                            <?= htmlspecialchars($personaje['status'] ?? 'Unknown') ?>
                        </p>

                        <!-- ESPECIE -->
                        <p>
                            <strong>Especie:</strong>
                            <?= htmlspecialchars($personaje['species'] ?? 'Sin información') ?>
                        </p>

                        <!-- TIPO -->
                        <p>
                            <strong>Tipo:</strong>
                            <?= !empty($personaje['type'])
                                ? htmlspecialchars($personaje['type'])
                                : 'Sin información' ?>
                        </p>

                        <!-- GÉNERO -->
                        <p>
                            <strong>Género:</strong>
                            <?= htmlspecialchars($personaje['gender'] ?? 'Sin información') ?>
                        </p>

                        <!-- ORIGEN -->
                        <p>
                            <strong>Origen:</strong>
                            <?= !empty($personaje['origin'])
                                ? htmlspecialchars($personaje['origin'])
                                : 'Sin información' ?>
                        </p>

                        <!-- UBICACIÓN -->
                        <p>
                            <strong>Ubicación:</strong>
                            <?= !empty($personaje['location_name'])
                                ? htmlspecialchars($personaje['location_name'])
                                : 'Sin información' ?>
                        </p>

                        <!-- VER DETALLES -->
                        <a
                            class="btn-detalle"
                            href="verdetalles.php?id=<?= (int) $personaje['id'] ?>">
                            Ver detalles
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

        <div class="paginacion">

            <?php
            // Armamos los parámetros actuales para conservar los filtros
            $parametrosUrl = [
                'search' => $search,
                'status' => $status,
                'species' => $species,
                'gender' => $gender
            ];

            // Función para generar la URL de cada página
            function urlPagina($numeroPagina, $parametrosUrl)
            {
                $parametrosUrl['page'] = $numeroPagina;

                return '?' . http_build_query($parametrosUrl);
            }
            ?>

            <!-- ANTERIOR -->
            <?php if ($page > 1): ?>

                <a href="<?= htmlspecialchars(urlPagina($page - 1, $parametrosUrl)) ?>">
                    Anterior
                </a>

            <?php endif; ?>


            <!-- NÚMEROS DE PÁGINA -->
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

                <a
                    href="<?= htmlspecialchars(urlPagina($i, $parametrosUrl)) ?>"
                    class="<?= $i === $page ? 'pagina-activa' : '' ?>">
                    <?= $i ?>
                </a>

            <?php endfor; ?>


            <!-- SIGUIENTE -->
            <?php if ($page < $totalPaginas): ?>

                <a href="<?= htmlspecialchars(urlPagina($page + 1, $parametrosUrl)) ?>">
                    Siguiente
                </a>

            <?php endif; ?>

        </div>


    </main>


</body>

</html>