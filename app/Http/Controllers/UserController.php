<?php

namespace App\Http\Controllers;

use App\Core\View\AuthExtension;
use App\Http\Request;
use App\Models\Answer;
use App\Models\Education;
use App\Models\Question;
use App\Models\TestLog;
use App\Models\TestResult;
use App\Models\User;

class UserController extends Controller
{
    protected $userModel;
    protected $testResultModel;
    protected $testLogModel;

    public function __construct()
    {
        parent::__construct();

        $this->userModel = new User();
        $this->testResultModel = new TestResult();
        $this->testLogModel = new TestLog();

        $authExtension = new AuthExtension(auth());

        if ($authExtension->hasPermission('users.manage') === false) {
            redirect('/');
        }
    }

    public function index()
    {
        $users = $this->userModel->all();

        echo $this->view('users/index.html', [
            'users' => $users
        ]);
    }

    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        $roles = require BASE_DIR . '/config/roles.php';
        $testResults = $this->testResultModel->whereUserId($id);
        $educations = (new Education())->whereUserId($user['id']);

        $testLogs = [];

        foreach ($testResults as $testResult) {
            $testLogs[$testResult['id']] = $this->testLogModel->logUserByTestId($testResult['user_id'], $testResult['test_id']);

            $number = 1;
            foreach ($testLogs[$testResult['id']] as $k => $testLog) {
                $testLogs[$testResult['id']][$k]['question'] = (new Question())->find($testLog['question_id']);
                $testLogs[$testResult['id']][$k]['answer'] = (new Answer())->find($testLog['answer_id']);
                $testLogs[$testResult['id']][$k]['correctly_answer'] = (new Answer())->find($testLog['correctly']);
                $testLogs[$testResult['id']][$k]['number'] = $number;

                $number++;
            }
        }

        echo $this->view('users/edit.html', [
            'user' => $user,
            'roles' => $roles,
            'testResults' => $testResults,
            'testLogs' => $testLogs,
            'educations' => $educations,
        ]);
    }

    public function update(int $id)
    {
        $request = new Request();

        $file = $request->file('photo');

        $photo = null;

        if ($file) {
            $nameFile = basename($file['name']);
            $uploadDir = BASE_DIR . '/public/storage/';
            $uploadFile = $uploadDir . $nameFile;

            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $photo = $nameFile;
            }
        }

        $attributes = [
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'middle_name' => $request->get('middle_name'),
            'position' => $request->get('position'),
            'job_date' => $request->get('job_date'),
            'role_id' => $request->get('role_id')
        ];

        if ($photo !== null) {
            $attributes['photo'] = $photo;
        }

        (new User())->update($id, $attributes);

        redirect('/admin/users');
    }
}
