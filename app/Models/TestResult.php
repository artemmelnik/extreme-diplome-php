<?php

namespace App\Models;

class TestResult extends Model
{
    protected static $table = 'test_results';

    public function whereUserId(int $userId)
    {
        $sql = '
          SELECT 
            t.title, 
            tr.status,
            tr.result_time,
            tr.result_answers,
            tr.result_correct_answers,
            tr.created_at
          FROM ' . static::$table . ' as tr
            JOIN tests as t ON tr.test_id = t.id
            WHERE tr.user_id = :user_id
        ';

        return $this->db->query($sql, [
            'user_id' => $userId
        ]);
    }

    public function whereHasUserIdAndTestId(int $userId, int $testId)
    {
        $query = $this->db->query('SELECT * FROM ' . static::$table . ' WHERE user_id = :user_id AND test_id = :test_id', [
            'user_id' => $userId,
            'test_id' => $testId
        ]);

        return $query[0] ?? null;
    }
}
