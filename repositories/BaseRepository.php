<?php

// Patron Singleton

abstract class BaseRepository {
   
    // Instancia unica de PDO para la app
    // PDO?: Puede ser pdo o null
    
    private static ?PDO $pdoInstance = null;
    protected PDO $pdo;
    
    public function __construct() {
        
        // Si tengo conexion creada la utilizo
        // si no , la inicializo por unica vez
        
        if(self::$pdoInstance==null){
            
            $dsn = "mysql:host=localhost;dbname=rick_and_morty_db";
            $usuario = "root";
            $pass = "";
            
            try{
                
                self::$pdoInstance = new PDO($dsn,$usuario,$pass);
                self::$pdoInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch (PDOException $ex) {
                
                die($ex->getMessage()); 
                
            }
            
            $this->pdo = self::$pdoInstance;
            
        }
        
        
    }

    
}
