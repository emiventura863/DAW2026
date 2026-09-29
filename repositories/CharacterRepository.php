<?php

require_once "controllers/CharacterController.php";

class CharacterRepository extends BaseRepository {

    function prueba() {

        echo $this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }

    function create(Character $character) {

        $stmt = $this->pdo->prepare("INSERT INTO characters(id,name,status,species,type,gender,origin,location_id)"
                . "VALUES (:id,:name,:status,:species,:type,:gender,:origin,:location_id)");

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
}
