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
            INSERT INTO characters (id, name, status, species, type, gender, origin, location_id)
            VALUES (:id, :name, :status, :species, :type, :gender, :origin, :location_id)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                status = VALUES(status),
                species = VALUES(species),
                type = VALUES(type),
                gender = VALUES(gender),
                origin = VALUES(origin),
                location_id = VALUES(location_id)
        ");

        $id = is_object($character) ? $character->getId() : $character['id'];
        $name = is_object($character) ? $character->getName() : $character['name'];
        $status = is_object($character) ? $character->getStatus() : $character['status'];
        $species = is_object($character) ? $character->getSpecies() : $character['species'];
        $type = is_object($character) ? $character->getType() : $character['type'];
        $gender = is_object($character) ? $character->getGender() : $character['gender'];
        $origin = is_object($character) ? $character->getOrigin() : ($character['origin']['name'] ?? null);
        $location_id = is_object($character) ? $character->getLocation_id() : null;

        $stmt->execute([
            ":id" => $character->getId(),
            ":name" => $character->getName(),
            ":status" => $character->getStatus(),
            ":species" => $character->getSpecies(),
            ":type" => $character->getType(),
            ":gender" => $character->getGender(),
            ":origin" => $character->getOrigin(),
            ":location_id" => $character->getLocation_id()
        ]);
    }

    function find(int $id)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM characters WHERE id=:id");

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch();
    }

    // Arma el pedazo "WHERE ..." + los parametros, compartido entre findAll y countAll
    private function armarFiltro(?string $search, ?string $status): array
    {

        $condiciones = [];
        $parametros = [];

        if ($search !== null && $search !== "") {
            $condiciones[] = "name LIKE :search";
            $parametros[":search"] = "%" . $search . "%";
        }

        if ($status !== null && $status !== "") {
            $condiciones[] = "status = :status";
            $parametros[":status"] = $status;
        }

        $whereSql = count($condiciones) > 0
            ? "WHERE " . implode(" AND ", $condiciones)
            : "";

        return [$whereSql, $parametros];
    }

    public function findAll(int $page = 1, int $perPage = 20, ?string $search = null, ?string $status = null): array
    {

        [$whereSql, $parametros] = $this->armarFiltro($search, $status);

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM characters $whereSql ORDER BY id LIMIT :limit OFFSET :offset";

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

    public function countAll(?string $search = null, ?string $status = null): int
    {

        [$whereSql, $parametros] = $this->armarFiltro($search, $status);

        $sql = "SELECT COUNT(*) FROM characters $whereSql";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametros);

        return (int) $stmt->fetchColumn();
    }
}
