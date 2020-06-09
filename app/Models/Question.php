<?php

namespace App\Models;

class Question extends Model
{
    protected static $table = 'questions';

    public function whereTestId(int $testId)
    {
        $query = $this->db->query('SELECT * FROM ' . static::$table . ' WHERE test_id = :test_id', [
            'test_id' => $testId
        ]);

        return $query;
    }
}
