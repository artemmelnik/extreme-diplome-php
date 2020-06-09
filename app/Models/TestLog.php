<?php

namespace App\Models;

class TestLog extends Model
{
    protected static $table = 'test_logs';

    public function isUserAnswer(int $userId, int $questionId)
    {
        $query = $this->db->query('SELECT COUNT(id) as count FROM ' . static::$table . ' WHERE user_id = :user_id AND question_id = :question_id', [
            'user_id' => $userId,
            'question_id' => $questionId,
        ]);

        if ($query[0]['count'] > 0) {
            return true;
        }

        return false;
    }

    public function logUserByTestId(int $userId, int $testId)
    {
        $query = $this->db->query('SELECT * FROM ' . static::$table . ' WHERE user_id = :user_id AND test_id = :test_id', [
            'user_id' => $userId,
            'test_id' => $testId,
        ]);

        return $query;
    }
}
