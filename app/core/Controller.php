<?php

class Controller
{
    protected $view;
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->view = new View();
        $this->request = App::getInstance()->getRequest();
        $this->response = App::getInstance()->getResponse();
    }

    /**
     * Renderizar vista
     */
    protected function render($view, $data = [])
    {
        return $this->view->render($view, $data);
    }

    /**
     * Renderizar vista con layout
     */
    protected function renderWithLayout($view, $data = [], $layout = 'layouts/main')
    {
        return $this->view->renderWithLayout($view, $data, $layout);
    }

    /**
     * Redireccionar
     */
    protected function redirect($url, $statusCode = 302)
    {
        $this->response->redirect($url, $statusCode);
    }

    /**
     * Respuesta JSON
     */
    protected function json($data, $statusCode = 200)
    {
        return $this->response->json($data, $statusCode);
    }

    /**
     * Verificar si es petición AJAX
     */
    protected function isAjax()
    {
        return $this->request->isAjax();
    }

    /**
     * Obtener datos POST
     */
    protected function getPost($key = null, $default = null)
    {
        return $this->request->getPost($key, $default);
    }

    /**
     * Obtener datos GET
     */
    protected function getQuery($key = null, $default = null)
    {
        return $this->request->getQuery($key, $default);
    }

    /**
     * Obtener un valor de entrada sin importar si vino como formulario
     * (application/x-www-form-urlencoded, $_POST) o como JSON en el
     * cuerpo de la petición (application/json, usado por las llamadas
     * AJAX del carrito).
     */
    protected function getInput($key, $default = null)
    {
        $value = $this->request->getPost($key);
        if ($value !== null) {
            return $value;
        }

        return $this->request->getJson($key, $default);
    }

    /**
     * Validar token CSRF.
     *
     * Comprueba, en orden: el campo de formulario "csrf_token", la
     * cabecera "X-CSRF-TOKEN" (usada por todas las llamadas AJAX del
     * frontend, ver main.php: $.ajaxSetup) y, por último, un
     * "csrf_token" dentro de un cuerpo JSON. Antes de este cambio solo
     * se comprobaba el campo de formulario, así que cualquier petición
     * AJAX con Content-Type: application/json (como /cart/add) no tenía
     * forma de pasar la validación aunque el cliente sí enviara el
     * token correcto por cabecera.
     */
    protected function validateCsrf()
    {
        $token = $this->getPost('csrf_token');

        if (!$token) {
            $token = $this->request->getHeader('X-CSRF-TOKEN');
        }

        if (!$token) {
            $token = $this->request->getJson('csrf_token');
        }

        if (!Session::validateCsrf($token)) {
            throw new Exception('Token CSRF inválido');
        }
    }
}