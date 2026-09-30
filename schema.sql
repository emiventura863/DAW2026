CREATE DATABASE IF NOT EXISTS rick_and_morty_db;
USE rick_and_morty_db;

CREATE TABLE IF NOT EXISTS locations (
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NULL,
    dimension VARCHAR(100) NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS characters (
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    status VARCHAR(50) NULL,
    species VARCHAR(100) NULL,
    type VARCHAR(100) NULL,
    gender VARCHAR(50) NULL,
    origin VARCHAR(255) NULL,
    location_id INT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_character_location FOREIGN KEY (location_id) REFERENCES locations(id)
);

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
    CONSTRAINT fk_ce_character FOREIGN KEY (character_id) REFERENCES characters(id),
    CONSTRAINT fk_ce_episode FOREIGN KEY (episode_id) REFERENCES episodes(id)
);