<?php

namespace App\Http\Controllers;

use App\Core\View\AuthExtension;
use App\Core\View\SessionExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(BASE_DIR . '/templates');
        $this->twig = new Environment($loader);

        $this->twig->addGlobal('auth', new AuthExtension(auth()));
        $this->twig->addGlobal('session', new SessionExtension());
    }

    public function view(string $template, array $data = []): string
    {
        /*$data = array_merge($data, [
            'auth' => auth()
        ]);*/

        return $this->twig->render($template, $data);
    }
}
