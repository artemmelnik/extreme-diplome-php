<?php

namespace App\Http;

class Request
{
    protected $request;
    protected $files;

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->files = $_FILES;
    }

    public function get(string $key)
    {
        return $this->request[$key] ?? null;
    }

    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }
}
