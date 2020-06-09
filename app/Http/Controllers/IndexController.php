<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        echo $this->view('index.html', [
            'name' => 'Artem'
        ]);
    }
}
