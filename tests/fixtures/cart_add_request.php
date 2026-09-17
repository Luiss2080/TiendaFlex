<?php

/**
 * Fixture ejecutada como proceso PHP CLI aparte (ver
 * CartControllerTest::testEndToEndAddIgnoresClientSuppliedPrice).
 *
 * CartController::add() termina la ejecución con exit() a través de
 * Response::json(), así que no se puede invocar en el mismo proceso
 * que el test runner sin matarlo. Ejecutarlo como subproceso reproduce
 * el flujo real completo (Router -> CartController -> Response) tal
 * como lo haría una petición HTTP real, incluyendo el intento de
 * manipular el precio desde "afuera".
 *
 * Uso: php cart_add_request.php <product_id> <quantity> <spoofed_price> <csrf_mode>
 * csrf_mode es uno de: "valid" (genera un token real y lo manda),
 * "missing" (no manda csrf_token en absoluto), "wrong" (manda un
 * token inventado).
 */

require_once __DIR__ . '/../bootstrap.php';

$productId = $argv[1] ?? '2';
$quantity = $argv[2] ?? '1';
$spoofedPrice = $argv[3] ?? '0.01';
$csrfMode = $argv[4] ?? 'valid';

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/cart/add';
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Simula "el usuario cargó una página" (recibe un token real) y luego
// "envía el formulario", todo dentro del mismo proceso/sesión.
$realToken = Session::getCsrf();

$_POST = [
    'product_id' => $productId,
    'quantity' => $quantity,
    'price' => $spoofedPrice, // intento de manipular el precio
];

if ($csrfMode === 'valid') {
    $_POST['csrf_token'] = $realToken;
} elseif ($csrfMode === 'wrong') {
    $_POST['csrf_token'] = 'un-token-completamente-inventado';
}
// csrf_mode === 'missing': no se agrega csrf_token al POST.

$controller = new CartController();
$controller->add();
