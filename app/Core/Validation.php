<?php

namespace App\Core;

use App\Models\User;

class Validation
{
    protected $rules;

    protected $params;

    protected $messages;

    private $isAuth;

    public function __construct(array $params = [], array $rules = [], array $messages = [])
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->rules = $rules;
        $this->params = $params;
        $this->messages = $messages;
    }

    public function auth(bool $isAuth)
    {
        $this->isAuth = $isAuth;

        return $this;
    }

    public function validate()
    {
        unset($_SESSION['errors']);

        $errors = [];

        foreach ($this->params as $key => $value) {
            if (isset($this->rules[$key])
                && $this->rules[$key] === 'required'
                && $key !== 'email'
                && $this->isRequired($value) === false
            ) {
                $errors[] = $this->messages[$key];
            }

            if (isset($this->rules[$key])
                && $this->rules[$key] === 'required'
                && $key === 'email'
                && $this->isEmail($value) === false
            ) {
                $errors[] = $this->messages[$key];
            }

            if (isset($this->rules[$key])
                && $this->rules[$key] === 'required'
                && $key === 'confirm_password'
                && $this->isConfirmPassword($this->params['password'], $this->params['confirm_password']) === false
            ) {
                $errors[] = $this->messages[$key];
            }
        }

        if ($this->isAuth
            && $this->isAuth([
                'email' => $this->params['email'],
                'password' => $this->params['password'],
            ]) === false
        ) {
            $errors[] = 'Пользователь с таким E-mail не зарегистрирован';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;

            redirect($_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    private function isRequired(string $value)
    {
        return !(strlen($value) < 6);
    }

    private function isEmail(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    private function isConfirmPassword($password, $confirmPassword)
    {

        if ($password !== $confirmPassword) {
            return false;
        }

        return true;
    }

    private function isAuth(array $data)
    {
        $user = (new User)->findByEmail($data['email']);

        if ($user === null || ($user && $user['password'] !== md5($data['password']))) {
            return false;
        }

        return true;
    }

}
