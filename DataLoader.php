<?php

declare(strict_types=1);

class DataLoader {

    private array $data;

    public function __construct(string $filename) {
        $this->$data = $this->load($filename);
    }

    public function load(string $filename) : void {
        $file = file_get_contents($filename);
        $this->$data = json_decode($file);
    }

    public function getData() : array {
        return $this->$data;
    }

    public function setData(array $data) : void {
        $this->$data = $data;
    }
}