<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestLog;
use App\Models\TestResult;
use App\Services\TestService;

class TestController extends Controller
{
    protected $testModel;
    protected $answerModel;
    protected $questionModel;
    protected $testResultModel;
    protected $testLogModel;
    protected $testService;

    public function __construct()
    {
        parent::__construct();

        $this->testModel       = new Test;
        $this->questionModel   = new Question;
        $this->answerModel     = new Answer;
        $this->testResultModel = new TestResult;
        $this->testLogModel    = new TestLog;
        $this->testService     = new TestService();
    }

    public function testing(int $testId)
    {
        $test       = $this->testModel->find($testId);
        $questions  = $this->questionModel->whereTestId($testId);
        $user       = auth();
        $testResult = $this->testResultModel->whereHasUserIdAndTestId($user['id'], $testId);
        $logs       = $this->testLogModel->logUserByTestId($user['id'], $testId);

        $request = new Request;

        $numberQuestion = $request->get('numberQuestion') ?? 1;
        $status         = $request->get('status') ?? 0;
        $questionId     = $request->get('question_id');
        $answerId       = $request->get('answer_id');
        $correctly      = $request->get('correctly');

        $dateStart = new \DateTime($testResult['created_at']);
        $dateEnd   = (new \DateTime($testResult['created_at']))->modify('+58 minutes');
        $dateNow   = new \DateTime;
        $dateDiff  = $dateStart->diff($dateNow);

        $isEndTime = false;
        if ($dateDiff->h > 0 || $dateDiff->d > 0) {
            $isEndTime = true;
        }

        $number = 1;
        $countQuestions = count($questions);

        foreach ($questions as $key => $question) {
            $answers = $this->answerModel->whereQuestionId($question['id']);

            if ($numberQuestion == $number) {
                $questionId = $question['id'];
            }

            $questions[$key]['number'] = $number;
            $questions[$key]['answers'] = $answers;

            $number++;
        }

        $isUserAnswer = false;

        if ($questionId) {
            $isUserAnswer = $this->testLogModel->isUserAnswer($user['id'], $questionId);
        }

        $resultData = $this->testService->resultData($logs);

        // Если все вопросы пройдены или закончилось время
        if (($countQuestions < $numberQuestion || $isEndTime) && $testResult['status'] == 0) {
            $this->testResultModel->update($testResult['id'], [
                'status' => 1,
                'result_time' => sprintf('%s мин. %s сек.', $dateDiff->i, $dateDiff->s),
                'result_answers' => $resultData['count'],
                'result_correct_answers' => $resultData['countSuccess']
            ]);

            redirect('/testing/' . $testId . '?status=1');
        }

        if ($questionId && $answerId && $testResult['status'] == 0) {
            $lastNumber = $numberQuestion+1;

            // Начало теста
            if ($numberQuestion == 1 && $testResult === null) {
                $this->testResultModel->create([
                    'test_id' => $testId,
                    'user_id' => $user['id']
                ]);
            }

            // Создаем лог
            $this->testLogModel->create([
                'user_id' => $user['id'],
                'test_id' => $testId,
                'question_id' => $questionId,
                'answer_id' => $answerId,
                'correctly' => $correctly,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            redirect('/testing/' . $testId . '?numberQuestion=' . $lastNumber);
        } else {
            $status = $testResult['status'];
        }

        echo $this->view('tests/testing.html', [
            'test' => $test,
            'questions' => $questions,
            'numberQuestion' => $numberQuestion,
            'isUserAnswer' => $isUserAnswer,
            'dateDiff' => $dateDiff,
            'testResult' => $testResult,
            'dateEnd' => $dateEnd->format('H:i:s'),
            'status' => $status,
            'resultData' => $resultData,
        ]);
    }

    public function index()
    {
        $auth = auth();
        $tests = $this->testModel->all();

        foreach ($tests as $key => $item) {
            $result = $this->testResultModel->whereHasUserIdAndTestId($auth['id'], $item['id']);
            $tests[$key]['auth_status'] = $result['status'] ?? 0;
        }

        echo $this->view('tests/index.html', [
            'tests' => $tests
        ]);
    }

    public function create()
    {
        echo $this->view('tests/create.html');
    }

    public function edit(int $id)
    {
        $test = (new Test())->find($id);
        $questions = (new Question())->whereTestId($id);

        echo $this->view('tests/edit.html', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }

    public function store()
    {
        $request = new Request();

        $title = $request->get('title');

        if (strlen($title) < 5) {
            exit;
        }

        try {
            (new Test)->create([
                'title' => $title,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $exception) {
            exit($exception->getMessage());
        }

        redirect('/admin/tests');
    }

    public function delete(int $id)
    {
        (new Test())->delete($id);

        foreach ((new Question())->whereTestId($id) as $item) {
            (new Answer())->clearByQuestionId($item['id']);
        }

        (new Question())->deleteWhereTestId($id);

        redirect('/admin/tests');
    }

}
