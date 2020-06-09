<?php

function redirect(string $uri) {
    header('Location: ' . $uri);
}

function auth() {
    global $db;

    $auth = new \App\Core\Auth\Auth();

    if ($auth->hashUser() == null) {
        return null;
    }

    $result = $db->query('SELECT * FROM users WHERE token = :token LIMIT 1', [
        'token' => $auth->hashUser()
    ]);

    return $result[0] ?? null;
}