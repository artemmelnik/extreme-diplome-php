<?php

namespace App\Http\Controllers;

use App\Core\Validation;
use App\Http\Request;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;

class QuestionController extends Controller
{
    public function create(int $testId)
    {
        $test = (new Test())->find($testId);

        echo $this->view('questions/create.html', [
            'test' => $test
        ]);
    }

    public function edit(int $id)
    {
        $question = (new Question())->find($id);
        $test = (new Test())->find($question['test_id']);
        $answers = (new Answer())->whereQuestionId($id);

        echo $this->view('questions/edit.html', [
            'test' => $test,
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    public function store(int $testId)
    {
        $request = new Request();

        $questionData = $request->get('question');
        $answers = $request->get('answers');
        $correctly = $answers['correctly'];

        $validation = new Validation(
            [
                'title' => $questionData['title']
            ],
            [
                'title' => 'required',
            ],
            [
                'title' => 'Введите вопрос'
            ]
        );

        $validation->validate();

        $question = (new Question())->create([
            'test_id' => $testId,
            'title' => $questionData['title'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $questionId = (int) $question['id'];

        foreach ($answers['title'] as $id => $answer) {
            $isCorrectly = 0;

            if ($id === $correctly) {
                $isCorrectly = 1;
            }

            (new Answer())->create([
                'question_id' => $questionId,
                'title' => $answer,
                'correctly' => $isCorrectly,
            ]);
        }

        redirect(sprintf('/admin/tests/%s/edit', $testId));
    }

    public function update(int $id)
    {
        $request = new Request();

        $questionData = $request->get('question');
        $answers = $request->get('answers');
        $correctly = $answers['correctly'];

        $validation = new Validation(
            [
                'title' => $questionData['title']
            ],
            [
                'title' => 'required',
            ],
            [
                'title' => 'Введите вопрос'
            ]
        );

        $validation->validate();

        $question = (new Question())->update($id, [
            'title' => $questionData['title']
        ]);

        $questionId = (int) $question['id'];

        (new Answer())->clearByQuestionId($questionId);

        foreach ($answers['title'] as $qid => $answer) {
            $isCorrectly = 0;

            if ($qid == $correctly) {
                $isCorrectly = 1;
            }

            (new Answer())->create([
                'question_id' => $questionId,
                'title' => $answer,
                'correctly' => $isCorrectly,
            ]);
        }

        redirect(sprintf('/admin/tests/%s/edit', $question['test_id']));
    }
}
