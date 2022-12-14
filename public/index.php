<?php

define('APPROOT', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';



error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');



session_start();

$router = new Core\Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('menu', ['controller' => 'Profile', 'action' => 'menu']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);
$router->add('{controller}/{action}');

$router->add('api/expenseSum/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}/{date:[\d-]+}', ['controller' => 'Expense', 'action' => 'expenseSumMonthly']);
$router->add('api/limit/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]+}', ['controller' => 'Expense', 'action' => 'limit']);


$router->dispatch($_SERVER['QUERY_STRING']);
