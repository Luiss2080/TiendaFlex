<?php

class Response
{
    private $headers = [];
    private $statusCode = 200;
    private $content = '';

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function send()
    {
        // Emitir código de estado y cabeceras solo cuando hay una
        // petición HTTP real detrás (servidor web real, o el servidor
        // embebido de PHP, cuyo SAPI es "cli-server"). Bajo el SAPI
        // "cli" puro (scripts de línea de comandos, incluidos los
        // tests automatizados de tests/run.php) no existe una
        // respuesta HTTP a la que ponerle cabeceras, y llamar de todos
        // modos a http_response_code()/header() solo genera avisos de
        // PHP ("headers already sent") en cuanto cualquier otra cosa
        // ya escribió algo a stdout antes.
        if (PHP_SAPI !== 'cli') {
            http_response_code($this->statusCode);

            foreach ($this->headers as $name => $value) {
                header("$name: $value");
            }
        }

        // Enviar contenido
        echo $this->content;
    }

    public function redirect($url, $statusCode = 302)
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        $this->send();
        exit;
    }

    public function json($data, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data));
        $this->send();
        exit;
    }

    public function notFound()
    {
        $this->setStatusCode(404);
        $this->setContent('404 - Página no encontrada');
        $this->send();
        exit;
    }

    public function forbidden()
    {
        $this->setStatusCode(403);
        $this->setContent('403 - Acceso denegado');
        $this->send();
        exit;
    }

    public function internalError($message = 'Error interno del servidor')
    {
        $this->setStatusCode(500);
        $this->setContent("500 - $message");
        $this->send();
        exit;
    }
}