<?php

require_once "BaseRepository.php";

class CharacterRepository extends BaseRepository {

    public function __construct() {
        parent::__construct();
    }

    public function prueba() {
        echo $this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    public function create($character) {
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
            ":id"          => $id,
            ":name"        => $name,
            ":status"      => $status,
            ":species"     => $species,
            ":type"        => $type,
            ":gender"      => $gender,
            ":origin"      => $origin,
            ":location_id" => $location_id
        ]);
    }
}