<?php

declare(strict_types=1);

class Request {
    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function get(string $key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool {
        return isset($this->data[$key]);
    }
}

?>
