<?php

class CharacterRepository extends BaseRepository {

    function prueba() {

        echo $this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }
}
