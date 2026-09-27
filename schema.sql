
CREATE TABLE IF NOT EXISTS episodes (
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    air_date VARCHAR(100) NULL,
    episode VARCHAR(50) NULL,
    created VARCHAR(100) NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS character_episode (
    character_id INT NOT NULL,
    episode_id INT NOT NULL,
    PRIMARY KEY (character_id, episode_id),
    INDEX (character_id),
    INDEX (episode_id)
);