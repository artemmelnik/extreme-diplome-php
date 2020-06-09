<?php

namespace App\Models;

class Answer extends Model
{
    protected static $table = 'answers';

    public function whereQuestionId(int $questionId)
    {
        $query = $this->db->query('SELECT * FROM ' . static::$table . ' WHERE question_id = :question_id', [
            'question_id' => $questionId
        ]);

        return $query;
    }

    public function clearByQuestionId(int $questionId): void
    {
        $this->db->query('DELETE FROM ' . static::$table . ' WHERE question_id = :question_id', [
            'question_id' => $questionId
        ]);
    }
}
