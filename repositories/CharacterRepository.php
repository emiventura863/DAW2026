<?php

require_once "BaseRepository.php";

class CharacterRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function prueba()
    {
        echo $this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    public function create($character)
    {
        if ($this->pdo === null) {
            parent::__construct();
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO characters (id, name, status, species, type, gender, image, origin, location_id)
            VALUES (:id, :name, :status, :species, :type, :gender, :image, :origin, :location_id)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                status = VALUES(status),
                species = VALUES(species),
                type = VALUES(type),
                gender = VALUES(gender),
                image = VALUES(image),
                origin = VALUES(origin),
                location_id = VALUES(location_id)
        ");

        // Extraemos los valores de forma segura manejando tanto objetos como arrays
        $id = is_object($character) ? $character->getId() : $character['id'];
        $name = is_object($character) ? $character->getName() : $character['name'];
        $status = is_object($character) ? $character->getStatus() : $character['status'];
        $species = is_object($character) ? $character->getSpecies() : $character['species'];
        $type = is_object($character) ? $character->getType() : $character['type'];
        $gender = is_object($character) ? $character->getGender() : $character['gender'];
        $image = is_object($character) ? $character->getImage() : ($character['image'] ?? null);
        $origin = is_object($character) ? $character->getOrigin() : ($character['origin'] ?? null);
        $location_id = is_object($character) ? $character->getLocation_id() : ($character['location_id'] ?? null);

        // Ejecutamos pasando directamente las variables ya limpias y aseguradas
        $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":status" => $status,
            ":species" => $species,
            ":type" => $type,
            ":gender" => $gender,
            ":image" => $image,
            ":origin" => $origin,
            ":location_id" => $location_id
        ]);
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM characters WHERE id=:id");

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch();
    }

    // Arma el WHERE y los parámetros para los filtros del listado
    private function armarFiltro(
        ?string $search,
        ?string $status,
        ?string $species,
        ?string $gender
    ): array {
        $condiciones = [];
        $parametros = [];

        // Buscar por nombre
        if ($search !== null && $search !== "") {
            $condiciones[] = "c.name LIKE :search";
            $parametros[":search"] = "%" . $search . "%";
        }

        // Filtrar por estado
        if ($status !== null && $status !== "") {
            $condiciones[] = "c.status = :status";
            $parametros[":status"] = $status;
        }

        // Filtrar por especie
        if ($species !== null && $species !== "") {
            $condiciones[] = "c.species = :species";
            $parametros[":species"] = $species;
        }

        // Filtrar por género
        if ($gender !== null && $gender !== "") {
            $condiciones[] = "c.gender = :gender";
            $parametros[":gender"] = $gender;
        }

        $whereSql = count($condiciones) > 0
            ? "WHERE " . implode(" AND ", $condiciones)
            : "";

        return [$whereSql, $parametros];
    }

    public function findAll(
        int $page = 1,
        int $perPage = 20,
        ?string $search = null,
        ?string $status = null,
        ?string $species = null,
        ?string $gender = null
    ): array {
        [$whereSql, $parametros] = $this->armarFiltro(
            $search,
            $status,
            $species,
            $gender
        );
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT
            c.*,
            l.name AS location_name
        FROM characters c
        LEFT JOIN locations l ON c.location_id = l.id
        $whereSql
        LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        // Los parametros de texto se atan normal
        foreach ($parametros as $clave => $valor) {
            $stmt->bindValue($clave, $valor);
        }

        // limit y offset necesitan tipo INT explicito
        $stmt->bindValue(":limit", $perPage, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countAll(
        ?string $search = null,
        ?string $status = null,
        ?string $species = null,
        ?string $gender = null
    ): int {
        [$whereSql, $parametros] = $this->armarFiltro(
            $search,
            $status,
            $species,
            $gender
        );

        $sql = "SELECT COUNT(*) FROM characters c $whereSql";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);

        return (int) $stmt->fetchColumn();
    }

    // Busca un personaje por su ID y obtiene también el nombre de su ubicación
    public function findById(int $id): ?array
    {
        $sql = "SELECT
                c.*,
                l.name AS location_name
            FROM characters c
            LEFT JOIN locations l ON c.location_id = l.id
            WHERE c.id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        $personaje = $stmt->fetch(PDO::FETCH_ASSOC);

        return $personaje ?: null;
    }

    // Busca todos los episodios asociados a un personaje mediante la tabla intermedia
    public function findEpisodesByCharacterId(int $characterId): array
    {
        $sql = "SELECT
                e.id,
                e.name,
                e.air_date,
                e.episode
            FROM episodes e
            INNER JOIN character_episode ce
                ON e.id = ce.episode_id
            WHERE ce.character_id = :character_id
            ORDER BY e.id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':character_id' => $characterId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
