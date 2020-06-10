<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
{
    protected $documentModel;

    public function __construct()
    {
        parent::__construct();

        $this->documentModel = new Document();
    }

    public function index()
    {
        $documents = $this->documentModel->all();

        echo $this->view('documents/index.html', [
            'documents' => $documents
        ]);
    }

    public function create()
    {
        echo $this->view('documents/create.html');
    }

    public function edit(int $id)
    {

    }

    public function store()
    {
        $request = new Request();

        $name = $request->get('name');
        $file = $request->file('file');

        if ($file) {
            $nameFile = basename($file['name']);
            $uploadDir = BASE_DIR . '/public/storage/';
            $uploadFile = $uploadDir . $nameFile;

            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $this->documentModel->create([
                    'name' => $name,
                    'file' => $nameFile
                ]);

                redirect('/admin/documents');
            }
        }
    }

    public function delete(int $id)
    {
        $file = $this->documentModel->find($id);

        if (unlink(BASE_DIR . '/public/storage/' . $file['file'])) {
            $this->documentModel->delete($id);
        }

        redirect('/admin/documents');
    }
}
