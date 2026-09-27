<?php

class Character {

    private $id;
    private $name;
    private $status;
    private $species;
    private $type;
    private $gender;
    private $origin;
    private $location_id;
    private $episode;

    public function __construct($id, $name, $status, $species, $type, $gender, $origin, $location_id, $episode) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->type = $type;
        $this->gender = $gender;
        $this->origin = $origin;
        $this->location_id = $location_id;
        $this->episode = $episode;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getSpecies() {
        return $this->species;
    }

    public function getType() {
        return $this->type;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getOrigin() {
        return $this->origin;
    }

    public function getLocation_id() {
        return $this->location_id;
    }

    public function getEpisode() {
        return $this->episode;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function setStatus($status): void {
        $this->status = $status;
    }

    public function setSpecies($species): void {
        $this->species = $species;
    }

    public function setType($type): void {
        $this->type = $type;
    }

    public function setGender($gender): void {
        $this->gender = $gender;
    }

    public function setOrigin($origin): void {
        $this->origin = $origin;
    }

    public function setLocation_id($location_id): void {
        $this->location_id = $location_id;
    }

    public function setEpisode($episode): void {
        $this->episode = $episode;
    }
}
