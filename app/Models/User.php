<?php

namespace App\Models;

class User extends Model
{
    protected static $table = 'users';

    public function findByEmail(string $email)
    {
        $query = $this->db->query('SELECT * FROM ' . static::$table . ' WHERE email = :email LIMIT 1', [
            'email' => $email
        ]);

        return $query[0] ?? null;
    }
}
