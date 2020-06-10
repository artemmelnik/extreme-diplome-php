<?php error_reporting(0);

session_start();

date_default_timezone_set('Europe/Kiev');

require 'vendor/autoload.php';

define('BASE_DIR', __DIR__);

use App\Core\Database;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('account', new Route('/', [
    '_controller' => 'AccountController@index'
]));

$routes->add('auth.signup', new Route('/signup', [
    '_controller' => 'AuthController@signup',
]));

$routes->add('login', new Route('/login', [
    '_controller' => 'AuthController@login',
]));

$routes->add('auth.register', new Route('/auth/register', [
    '_controller' => 'AuthController@handleRegister'
]));

$routes->add('auth.login', new Route('/auth/login', [
    '_controller' => 'AuthController@handleLogin'
]));

$routes->add('logout', new Route('/auth/logout', [
    '_controller' => 'AuthController@logout'
]));

$routes->add('account.update', new Route('/account/update', [
    '_controller' => 'AccountController@update'
]));

$routes->add('admin.tests', new Route('/admin/tests', [
    '_controller' => 'TestController@index'
]));

$routes->add('admin.tests.create', new Route('/admin/tests/create', [
    '_controller' => 'TestController@create'
]));

$routes->add('admin.tests.edit', new Route('/admin/tests/{id}/edit', [
    '_controller' => 'TestController@edit'
]));

$routes->add('admin.tests.store', new Route('/admin/tests/store', [
    '_controller' => 'TestController@store'
]));

$routes->add('admin.tests.delete', new Route('/admin/tests/{id}/delete', [
    '_controller' => 'TestController@delete'
]));

$routes->add('admin.questions.create', new Route('/admin/questions/{testId}/create', [
    '_controller' => 'QuestionController@create'
]));

$routes->add('admin.questions.edit', new Route('/admin/questions/{id}/edit', [
    '_controller' => 'QuestionController@edit'
]));

$routes->add('admin.questions.store', new Route('/admin/questions/{testId}/store', [
    '_controller' => 'QuestionController@store'
]));

$routes->add('admin.questions.update', new Route('/admin/questions/{id}/update', [
    '_controller' => 'QuestionController@update'
]));

$routes->add('tests.testing', new Route('/testing/{testId}', [
    '_controller' => 'TestController@testing'
]));

$routes->add('admin.documents', new Route('/admin/documents', [
    '_controller' => 'DocumentController@index'
]));

$routes->add('admin.documents.create', new Route('/admin/documents/create', [
    '_controller' => 'DocumentController@create'
]));

$routes->add('admin.documents.edit', new Route('/admin/documents/{id}/edit', [
    '_controller' => 'DocumentController@edit'
]));

$routes->add('admin.documents.store', new Route('/admin/documents/store', [
    '_controller' => 'DocumentController@store'
]));

$routes->add('admin.documents.delete', new Route('/admin/documents/{id}/delete', [
    '_controller' => 'DocumentController@delete'
]));

$routes->add('admin.users', new Route('/admin/users', [
    '_controller' => 'UserController@index'
]));

$routes->add('admin.users.edit', new Route('/admin/users/{id}/edit', [
    '_controller' => 'UserController@edit'
]));

$routes->add('admin.users.update', new Route('/admin/users/{id}/update', [
    '_controller' => 'UserController@update'
]));

$method = $_SERVER['REQUEST_METHOD'];

$context = new RequestContext('/', $method);

$matcher = new UrlMatcher($routes, $context);

try {

    $config = require BASE_DIR . '/config/database.php';

    $db = new Database([
        'host' => $config['host'],
        'dbname' => $config['db_name'],
        'user' => $config['username'],
        'password' => $config['password'],
        'charset' => $config['charset']
    ]);

    $parameters = $matcher->match($_SERVER['REQUEST_URI']);

    [$controllerName, $actionName] = explode('@', $parameters['_controller']);

    $controllerName = '\\App\\Http\\Controllers\\' . $controllerName;

    $controller = new $controllerName;

    unset($parameters['_controller'], $parameters['_route']);

    call_user_func_array([$controller, $actionName], $parameters);

    $db->closeConnection();

    session_destroy();

} catch (\Exception $exception) {
    exit($exception->getMessage());
}
