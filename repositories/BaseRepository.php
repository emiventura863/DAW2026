<?php

abstract class BaseRepository {

    private static ?PDO $pdoInstance = null;
    protected ?PDO $pdo = null; // Cambiado a ?PDO = null para evitar el bloqueo fatal

    public function __construct() {
        if (self::$pdoInstance === null) {
            $dsn = "mysql:host=localhost;dbname=rick_and_morty_db;charset=utf8mb4";
            $usuario = "root";
            $pass = "";

            try {
                self::$pdoInstance = new PDO($dsn, $usuario, $pass);
                self::$pdoInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $ex) {
                die("Error de conexión a la base de datos: " . $ex->getMessage());
            }
        }
        $this->pdo = self::$pdoInstance;
    }
}