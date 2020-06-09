<?php

namespace App\Core\View;

class SessionExtension
{
    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }
}
