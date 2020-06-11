<?php

namespace App\Models;

class Education extends Model
{
    protected static $table = 'educations';

    public function whereUserId(int $userId)
    {
        return $this->db->query('SELECT * FROM ' . static::$table . ' WHERE user_id = :user_id', [
            'user_id' => $userId
        ]);
    }
}
