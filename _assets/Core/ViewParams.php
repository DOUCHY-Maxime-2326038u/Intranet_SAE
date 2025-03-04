<?php

class ViewParams
{
    private array $params = [];

    public function set(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function getAll(): array
    {
        return $this->params;
    }
}
