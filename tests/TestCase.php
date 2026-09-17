<?php

/**
 * Harness de pruebas mínimo, sin dependencias externas.
 *
 * El proyecto declara PHPUnit ^9 como dependencia de desarrollo, pero
 * ese entorno no tiene acceso garantizado a Internet/Packagist para
 * instalarlo, y PHPUnit 9 no es compatible con versiones modernas de
 * PHP. Este harness cubre lo que pide la auditoría (cobertura real de
 * cálculo de totales de carrito y verificación de CSRF) sin depender
 * de nada fuera de PHP puro, así que corre igual en cualquier máquina
 * o pipeline de CI con solo `php` instalado.
 */

class TestAssertionFailed extends Exception
{
}

abstract class TestCase
{
    protected function assertTrue($condition, $message = 'Se esperaba true')
    {
        if ($condition !== true) {
            throw new TestAssertionFailed($message);
        }
    }

    protected function assertFalse($condition, $message = 'Se esperaba false')
    {
        if ($condition !== false) {
            throw new TestAssertionFailed($message);
        }
    }

    protected function assertSame($expected, $actual, $message = null)
    {
        if ($expected !== $actual) {
            $message = $message ?? sprintf(
                'Se esperaba %s pero se obtuvo %s',
                var_export($expected, true),
                var_export($actual, true)
            );
            throw new TestAssertionFailed($message);
        }
    }

    protected function assertGreaterThan($expected, $actual, $message = null)
    {
        if (!($actual > $expected)) {
            $message = $message ?? sprintf(
                'Se esperaba un valor mayor que %s, se obtuvo %s',
                var_export($expected, true),
                var_export($actual, true)
            );
            throw new TestAssertionFailed($message);
        }
    }

    /**
     * ReflectionProperty/ReflectionMethod::setAccessible() es un no-op
     * desde PHP 8.1 (todo es accesible por reflexión por defecto) y
     * genera un aviso "Deprecated" en PHP 8.5+. Sigue siendo necesario
     * en PHP 7.4, la versión mínima que declara composer.json. Este
     * helper evita imprimir avisos sueltos fuera de cualquier buffer de
     * salida, que de otro modo contaminan la salida capturada de OTROS
     * tests que sí verifican el contenido exacto de una respuesta.
     */
    protected function makeAccessible($reflector)
    {
        if (PHP_VERSION_ID < 80100) {
            $reflector->setAccessible(true);
        }
    }
}
