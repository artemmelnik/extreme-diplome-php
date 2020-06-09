<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Models\User;

class UserController extends Controller
{
    protected $userModel;
    protected $testResultModel;

    public function __construct()
    {
        parent::__construct();

        $this->userModel = new User();
        $this->testResultModel = new TestResult();
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

        echo $this->view('users/edit.html', [
            'user' => $user,
            'roles' => $roles,
            'testResults' => $testResults
        ]);
    }
}
