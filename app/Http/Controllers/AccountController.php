<?php

namespace App\Http\Controllers;

use App\Http\Request;
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

        echo $this->view('account/index.html', [
            'roles' => $roles,
            'user' => $user
        ]);
    }

    public function update()
    {
        $request = new Request();
        $user = auth();

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

        (new User())->update($user['id'], $attributes);

        redirect('/');
    }
}
