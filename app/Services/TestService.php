<?php

namespace App\Services;

class TestService
{
    public function resultData(array $logs)
    {
        $count = 0;
        $countSuccess = 0;

        foreach ($logs as $log) {
            if ($log['answer_id'] === $log['correctly']) {
                $countSuccess++;
            }

            $count++;
        }

        return [
            'count' => $count,
            'countSuccess' => $countSuccess
        ];
    }
}
