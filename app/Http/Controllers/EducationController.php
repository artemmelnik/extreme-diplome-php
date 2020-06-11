<?php

namespace App\Http\Controllers;

use App\Core\Validation;
use App\Http\Request;
use App\Models\Education;

class EducationController extends Controller
{
    protected $educationModel;

    public function __construct()
    {
        parent::__construct();

        $this->educationModel = new Education();
    }

    public function index()
    {
        $educations = $this->educationModel->all();

        echo $this->view('educations/index.html', [
            'documents' => $educations
        ]);
    }

    public function create()
    {
        echo $this->view('educations/create.html');
    }

    public function edit(int $id)
    {

    }

    public function store()
    {
        $request = new Request();

        $auth = auth();

        $name = $request->get('name');
        $date = $request->get('date');

        $validation = new Validation(
            [
                'name' => $name,
                'date' => $date
            ],
            [
                'name' => 'required',
                'date' => 'required'
            ],
            [
                'name' => 'Введите наименование',
                'date' => 'Введите дату'
            ]
        );

        $validation->validate();

        $this->educationModel->create([
            'user_id' => $auth['id'],
            'name' => $name,
            'date' => $date
        ]);

        redirect('/');

    }

    public function delete(int $id)
    {
        $this->educationModel->delete($id);

        redirect('/');
    }
}
