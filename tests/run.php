<?php

/**
 * Runner de pruebas sin dependencias. Uso: php tests/run.php
 *
 * Recorre tests/*Test.php, instancia cada clase que extienda TestCase
 * y ejecuta todos sus métodos public que empiecen con "test". Imprime
 * un resumen y termina con código de salida distinto de cero si algo
 * falló (para que un pipeline de CI lo detecte).
 */

require_once __DIR__ . '/bootstrap.php';

$testFiles = glob(__DIR__ . '/*Test.php');
sort($testFiles);

$totalTests = 0;
$totalFailures = 0;
$failureDetails = [];

foreach ($testFiles as $file) {
    require_once $file;

    $className = basename($file, '.php');

    if (!class_exists($className)) {
        continue;
    }

    $reflection = new ReflectionClass($className);
    if (!$reflection->isSubclassOf(TestCase::class) || $reflection->isAbstract()) {
        continue;
    }

    foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if (strpos($method->getName(), 'test') !== 0) {
            continue;
        }

        $totalTests++;
        $instance = $reflection->newInstance();
        $label = $className . '::' . $method->getName();

        try {
            $method->invoke($instance);
            echo "  [OK] $label\n";
        } catch (Throwable $e) {
            $totalFailures++;
            $failureDetails[] = "$label\n    " . get_class($e) . ': ' . $e->getMessage();
            echo "  [FAIL] $label -- " . $e->getMessage() . "\n";
        }
    }
}

echo "\n" . str_repeat('-', 60) . "\n";
echo "Total: $totalTests   Fallos: $totalFailures\n";

if ($totalFailures > 0) {
    echo "\nDetalle de fallos:\n";
    foreach ($failureDetails as $detail) {
        echo "- $detail\n";
    }
    exit(1);
}

exit(0);
