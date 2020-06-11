<?php

namespace App\Http\Controllers;

use App\Core\Validation;
use App\Http\Request;
use App\Models\Education;
use App\Models\User;

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (auth() === null) {
            redirect('/login');
        }
    }

    public function index()
    {
        $roles = require BASE_DIR . '/config/roles.php';
        $user = auth();
        $educations = (new Education())->whereUserId($user['id']);

        echo $this->view('account/index.html', [
            'roles' => $roles,
            'user' => $user,
            'educations' => $educations
        ]);
    }

    public function update()
    {
        $request = new Request();
        $user = auth();

        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $middleName = $request->get('middle_name');
        $position = $request->get('position');
        $jobDate = $request->get('job_date');
        $roleId = $request->get('role_id');

        $validation = new Validation(
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'position' => $position
            ],
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'position' => 'required',
            ],
            [
                'first_name' => 'Введите имя',
                'last_name' => 'Введите фамилию',
                'position' => 'Укажите должность',
            ]
        );

        $validation->validate();

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
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'position' => $position,
            'job_date' => $jobDate,
            'role_id' => $roleId
        ];

        if ($photo !== null) {
            $attributes['photo'] = $photo;
        }

        (new User())->update($user['id'], $attributes);

        redirect('/');
    }
}
