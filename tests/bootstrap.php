<?php

/**
 * Bootstrap mínimo para los tests.
 *
 * No arranca la aplicación completa (App::getInstance()->run()) a
 * propósito: eso dispararía el enrutador real, y con él una conexión a
 * base de datos para cualquier controlador que use un Model. Los tests
 * solo necesitan las clases del framework y los helpers cargados.
 */

error_reporting(E_ALL);

define('TESTS_ROOT', __DIR__);
define('APP_ROOT', dirname(__DIR__));

spl_autoload_register(function ($class) {
    $directories = [
        '/app/core/',
        '/app/controllers/',
        '/app/models/',
    ];

    foreach ($directories as $directory) {
        $file = APP_ROOT . $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once APP_ROOT . '/app/helpers/functions.php';

require_once TESTS_ROOT . '/TestCase.php';
