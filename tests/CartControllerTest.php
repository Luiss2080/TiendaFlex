<?php

/**
 * Cobertura del carrito: cálculo de totales y, sobre todo, que el
 * precio de cada línea SIEMPRE se resuelve del catálogo del servidor
 * y nunca de un valor enviado por el cliente (el clásico ataque de
 * "manipular el precio en el carrito").
 *
 * CartController::add()/update()/remove() terminan con exit() (vía
 * Response::json()), así que no se pueden invocar dentro del mismo
 * proceso que este test runner. Dos estrategias complementarias:
 *
 *  1. Invocar los métodos privados relevantes por reflexión, que no
 *     llaman a exit() y contienen toda la lógica de negocio real.
 *  2. Un test end-to-end que lanza un proceso PHP CLI aparte
 *     (tests/fixtures/cart_add_request.php) para ejercitar el flujo
 *     completo Router-equivalente -> CartController -> Response tal
 *     como lo haría una petición HTTP real, capturando su salida.
 */
class CartControllerTest extends TestCase
{
    private function callPrivate($object, $method, array $args = [])
    {
        $reflection = new ReflectionMethod($object, $method);
        $this->makeAccessible($reflection);
        return $reflection->invokeArgs($object, $args);
    }

    private function newCartController()
    {
        // Reiniciar el singleton de App para que tome un Request nuevo
        // (App::getInstance() cachea uno solo).
        $appReflection = new ReflectionClass(App::class);
        $instanceProperty = $appReflection->getProperty('instance');
        $this->makeAccessible($instanceProperty);
        $instanceProperty->setValue(null, null);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/cart';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_POST = [];

        unset($_SESSION['cart'], $_SESSION['cart_count']);

        return new CartController();
    }

    public function testSanitizeQuantityClampsToValidRange()
    {
        $cart = $this->newCartController();

        $this->assertSame(1, $this->callPrivate($cart, 'sanitizeQuantity', [-50]));
        $this->assertSame(1, $this->callPrivate($cart, 'sanitizeQuantity', [0]));
        $this->assertSame(5, $this->callPrivate($cart, 'sanitizeQuantity', [5]));
        $this->assertSame(100, $this->callPrivate($cart, 'sanitizeQuantity', [999999]));
    }

    public function testResolveServerPricePrefersSalePriceOverRegularPrice()
    {
        $cart = $this->newCartController();

        $onSale = ['id' => 3, 'price' => 360.00, 'sale_price' => 280.00];
        $noSale = ['id' => 2, 'price' => 480.00, 'sale_price' => null];

        $this->assertSame(280.0, $this->callPrivate($cart, 'resolveServerPrice', [$onSale]));
        $this->assertSame(480.0, $this->callPrivate($cart, 'resolveServerPrice', [$noSale]));
    }

    public function testFindProductOrFailIgnoresNonNumericAndUnknownIds()
    {
        $cart = $this->newCartController();

        $this->assertSame(null, $this->callPrivate($cart, 'findProductOrFail', ['DROP TABLE products;']));
        $this->assertSame(null, $this->callPrivate($cart, 'findProductOrFail', [999999]));

        $product = $this->callPrivate($cart, 'findProductOrFail', [2]);
        $this->assertTrue(is_array($product) && $product['id'] == 2);
    }

    public function testCartTotalIsSumOfServerResolvedPricesTimesQuantity()
    {
        $cart = $this->newCartController();

        // Sembrar el carrito directamente en la sesión, como si dos
        // artículos ya se hubieran agregado: 2 x $240.00 y 1 x $480.00.
        $_SESSION['cart'] = [
            '1' => ['product_id' => 1, 'name' => 'Gym Weight', 'price' => 240.00, 'quantity' => 2],
            '2' => ['product_id' => 2, 'name' => 'Cloud Nike Shoes', 'price' => 480.00, 'quantity' => 1],
        ];

        $totals = $this->callPrivate($cart, 'getCartWithTotals');

        $this->assertSame(3, $totals['count']);
        $this->assertSame(960.0, $totals['total']); // (240*2) + (480*1)
    }

    public function testEndToEndAddIgnoresClientSuppliedPriceAndRequiresCsrf()
    {
        $phpBinary = PHP_BINARY;
        $fixture = __DIR__ . '/fixtures/cart_add_request.php';

        // Producto 2 (Cloud Nike Shoes) cuesta $480.00 de verdad; se
        // manda un "price": 0.01 para intentar manipularlo.
        $successOutput = shell_exec(escapeshellarg($phpBinary) . ' ' . escapeshellarg($fixture) . ' 2 1 0.01 valid');
        $successResponse = json_decode(trim((string)$successOutput), true);

        $this->assertTrue(is_array($successResponse), 'La respuesta end-to-end debe ser JSON válido: ' . $successOutput);
        $this->assertTrue($successResponse['success'] ?? false);
        $this->assertSame(480.0, (float)$successResponse['cart_total']);

        $missingCsrfOutput = shell_exec(escapeshellarg($phpBinary) . ' ' . escapeshellarg($fixture) . ' 2 1 0.01 missing');
        $missingCsrfResponse = json_decode(trim((string)$missingCsrfOutput), true);
        $this->assertFalse($missingCsrfResponse['success'] ?? true, 'Una petición sin csrf_token no debe poder agregar al carrito');

        $wrongCsrfOutput = shell_exec(escapeshellarg($phpBinary) . ' ' . escapeshellarg($fixture) . ' 2 1 0.01 wrong');
        $wrongCsrfResponse = json_decode(trim((string)$wrongCsrfOutput), true);
        $this->assertFalse($wrongCsrfResponse['success'] ?? true, 'Un csrf_token inventado no debe poder agregar al carrito');
    }
}
