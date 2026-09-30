<?php

require_once "BaseRepository.php";

class LocationRepository extends BaseRepository {

    public function __construct() {
        parent::__construct();
    }

    public function create(array $location): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO locations (id, name, type, dimension)
            VALUES (:id, :name, :type, :dimension)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                type = VALUES(type),
                dimension = VALUES(dimension)
        ");

        $stmt->execute([
            ":id"        => $location['id'],
            ":name"      => $location['name'],
            ":type"      => $location['type'],
            ":dimension" => $location['dimension']
        ]);
    }
}