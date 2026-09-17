<?php

/**
 * Router::executeHandler() usaba a llamar al método del controlador
 * sin argumentos, así que cualquier ruta con {param} (como
 * /shop/product/{id}) nunca recibía el valor real -- ProductController
 * ::show($id) explotaba con un ArgumentCountError. Esta prueba
 * reproduce el mismo camino (Router real, Request real construido a
 * partir de variables de servidor simuladas) contra un controlador de
 * prueba, para que una regresión futura del mismo tipo se detecte aquí
 * en vez de en producción.
 */

class RouterParamsTestDummyController
{
    public static $lastReceivedId = null;

    public function show($id)
    {
        self::$lastReceivedId = $id;
        return 'id-recibido:' . $id;
    }

    public function noParams()
    {
        return 'sin-parametros';
    }
}

class RouterParamsTest extends TestCase
{
    private function dispatch($uri, $method = 'GET')
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $request = new Request();
        $response = new Response();
        $router = new Router($request, $response);

        return [$router, $request, $response];
    }

    public function testRouteParameterIsPassedToControllerMethod()
    {
        [$router] = $this->dispatch('/widgets/42');
        $router->get('/widgets/{id}', 'RouterParamsTestDummyController@show');

        RouterParamsTestDummyController::$lastReceivedId = null;

        ob_start();
        $router->dispatch();
        $output = ob_get_clean();

        $this->assertSame('42', RouterParamsTestDummyController::$lastReceivedId);
        $this->assertSame('id-recibido:42', $output);
    }

    public function testRouteWithoutParametersStillWorks()
    {
        [$router] = $this->dispatch('/widgets');
        $router->get('/widgets', 'RouterParamsTestDummyController@noParams');

        ob_start();
        $router->dispatch();
        $output = ob_get_clean();

        $this->assertSame('sin-parametros', $output);
    }
}
