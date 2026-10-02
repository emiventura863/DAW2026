<?php

require_once "BaseRepository.php";

class EpisodeRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    // Guarda un episodio y lo actualiza si ya existe en la base de datos
    public function create(array $episode): void
    {
        $sql = "INSERT INTO episodes (
                    id,
                    name,
                    air_date,
                    episode,
                    created
                )
                VALUES (
                    :id,
                    :name,
                    :air_date,
                    :episode,
                    :created
                )
                ON DUPLICATE KEY UPDATE
                    name = VALUES(name),
                    air_date = VALUES(air_date),
                    episode = VALUES(episode),
                    created = VALUES(created)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $episode['id'],
            ':name' => $episode['name'],
            ':air_date' => $episode['air_date'],
            ':episode' => $episode['episode'],
            ':created' => $episode['created']
        ]);
    }
}
