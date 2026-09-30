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

// CANTIDAD TOTAL DE RESULTADOS

$total = $cR->countAll($search, $status);


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
    $status
);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Personajes - Rick and Morty</title>

    <style>
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


            <?php foreach ($personajes as $p): ?>

                <article class="tarjeta">


                    <!-- IMAGEN DEL PERSONAJE -->

                    <img
                        src="<?= $p['image'] ?>"
                        alt="<?= htmlspecialchars($p['name']) ?>">


                    <!-- DATOS -->

                    <div class="datos">


                        <!-- NOMBRE -->

                        <h2>
                            <?= htmlspecialchars($p['name']) ?>
                        </h2>


                        <!-- STATUS -->

                        <p>

                            <strong>Status:</strong>

                            <?= htmlspecialchars($p['status']) ?>

                        </p>


                        <!-- ESPECIE -->

                        <p>

                            <strong>Especie:</strong>

                            <?= htmlspecialchars($p['species']) ?>

                        </p>


                        <!-- GÉNERO -->

                        <p>

                            <strong>Género:</strong>

                            <?= htmlspecialchars($p['gender']) ?>

                        </p>


                    </div>

                </article>


            <?php endforeach; ?>


        </section>


        <div class="paginacion">


            <?php if ($page > 1): ?>

                <!-- MANTENER SEARCH Y STATUS EN LOS ENLACES DE PAGINACIÓN -->

                <a
                    href="listado.php?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">
                    &laquo; Anterior
                </a>

            <?php endif; ?>


            <?php if ($page < $totalPaginas): ?>

                <a
                    href="listado.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">
                    Siguiente &raquo;
                </a>

            <?php endif; ?>


        </div>


    </main>


</body>

</html>