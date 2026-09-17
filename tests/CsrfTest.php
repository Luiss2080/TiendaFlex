<?php

/**
 * Cobertura de la verificación de CSRF (Session::generateCsrf /
 * getCsrf / validateCsrf), el mecanismo que protege el único
 * endpoint de escritura completamente cableado del sitio
 * (PageController::sendContact) y el que ahora también usa
 * CartController.
 */
class CsrfTest extends TestCase
{
    public function testGeneratedTokenValidatesSuccessfully()
    {
        $token = Session::generateCsrf();

        $this->assertTrue(is_string($token) && strlen($token) > 0, 'El token generado debe ser un string no vacío');
        $this->assertTrue(Session::validateCsrf($token), 'El token recién generado debe validar correctamente');
    }

    public function testWrongTokenIsRejected()
    {
        Session::generateCsrf();

        $this->assertFalse(Session::validateCsrf('un-token-completamente-inventado'));
    }

    public function testMissingTokenIsRejectedWithoutCrashing()
    {
        Session::generateCsrf();

        // Antes del fix, pasar null aquí (el caso de una petición que
        // no manda csrf_token en absoluto) provocaba un TypeError sin
        // capturar en hash_equals() bajo PHP 8+, en vez de un simple
        // "token inválido". Esto reproduce ese escenario exacto.
        $this->assertFalse(Session::validateCsrf(null));
    }

    public function testEmptyStringTokenIsRejected()
    {
        Session::generateCsrf();

        $this->assertFalse(Session::validateCsrf(''));
    }

    public function testGetCsrfGeneratesATokenWhenNoneExists()
    {
        unset($_SESSION['csrf_token']);

        $token = Session::getCsrf();

        $this->assertTrue(is_string($token) && strlen($token) === 64, 'getCsrf() debe generar un token hex de 32 bytes (64 chars) si no existe uno');
        $this->assertTrue(Session::validateCsrf($token));
    }
}
