<?php

namespace App\Http\Controllers;

use App\Core\Auth\Auth;
use App\Core\Validation;
use App\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (auth() !== null) {
            redirect('/account');
        }
    }

    public function signup()
    {
        echo $this->view('auth/signup.html', [
            'name' => 'Artem'
        ]);
    }

    public function login()
    {
        echo $this->view('auth/login.html');
    }

    public function handleRegister()
    {
        $request = new Request;

        $email           = $request->get('email');
        $password        = $request->get('password');
        $passwordConfirm = $request->get('confirm_password');

        $validation = new Validation(
            [
                'email' => $email,
                'password' => $password,
                'confirm_password' => $passwordConfirm,
            ],
            [
                'email' => 'required',
                'password' => 'required',
                'confirm_password' => 'required',
            ],
            [
                'email' => 'Введите корректный E-mail',
                'password' => 'Пароль должен содержать минимум 6 символов',
                'confirm_password' => 'Пароли не совпадают',
            ]
        );

        $validation->validate();

        $user = (new User)->create([
            'email' => $email,
            'password' => md5($password),
            'job_date' => date('Y-m-d'),
        ]);

        $this->auth($user);

        redirect('/');
    }

    public function handleLogin()
    {
        $request = new Request;

        $email    = $request->get('email');
        $password = $request->get('password');

        $validation = new Validation(
            [
                'email' => $email,
                'password' => $password,
            ],
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email' => 'Введите корректный E-mail',
                'password' => 'Пароль должен содержать минимум 6 символов'
            ]
        );

        $validation->auth(true)->validate();

        $user = (new User)->findByEmail($email);

        $this->auth($user);

        redirect('/');
    }

    private function auth($user)
    {
        $auth = new Auth();

        $hash = md5($user['id'] . $user['email'] . $user['password'] . $auth->salt());

        $user = (new User)->update($user['id'], [
            'token' => $hash
        ]);

        $auth->authorize($hash);
    }

    public function logout()
    {
        $auth = new Auth();

        $auth->unAuthorize();

        redirect('/login');
    }
}
