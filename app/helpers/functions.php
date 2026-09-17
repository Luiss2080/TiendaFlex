<?php

/**
 * Funciones auxiliares globales
 */

if (!function_exists('dd')) {
    /**
     * Dump and die - útil para debugging
     */
    function dd(...$vars) {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die();
    }
}

if (!function_exists('env')) {
    /**
     * Obtener variable de entorno
     */
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    /**
     * Obtener configuración
     */
    function config($key, $default = null) {
        $keys = explode('.', $key);
        $configFile = array_shift($keys);
        
        $configPath = __DIR__ . '/../../config/' . $configFile . '.php';
        
        if (!file_exists($configPath)) {
            return $default;
        }
        
        $config = require $configPath;
        
        foreach ($keys as $segment) {
            if (!isset($config[$segment])) {
                return $default;
            }
            $config = $config[$segment];
        }
        
        return $config;
    }
}

if (!function_exists('asset')) {
    /**
     * Generar URL para assets
     */
    function asset($path) {
        return View::asset($path);
    }
}

if (!function_exists('url')) {
    /**
     * Generar URL
     */
    function url($path = '') {
        return View::url($path);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redireccionar
     */
    function redirect($url, $statusCode = 302) {
        header("Location: $url", true, $statusCode);
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Obtener valor anterior del formulario
     */
    function old($key, $default = '') {
        return View::old($key, $default);
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generar campo CSRF
     */
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . View::csrf() . '">';
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Obtener token CSRF
     */
    function csrf_token() {
        return View::csrf();
    }
}

if (!function_exists('errors')) {
    /**
     * Obtener errores de validación
     */
    function errors($key = null) {
        return View::errors($key);
    }
}

if (!function_exists('has_errors')) {
    /**
     * Verificar si hay errores
     */
    function has_errors($key = null) {
        return View::hasErrors($key);
    }
}

if (!function_exists('flash')) {
    /**
     * Mensajes flash
     */
    function flash($key, $value = null) {
        if ($value !== null) {
            Session::flash($key, $value);
        } else {
            return Session::flash($key);
        }
    }
}

if (!function_exists('auth')) {
    /**
     * Usuario autenticado
     */
    function auth() {
        return Session::isAuthenticated();
    }
}

if (!function_exists('user')) {
    /**
     * Datos del usuario autenticado
     */
    function user($key = null) {
        return Session::getUserData($key);
    }
}

if (!function_exists('sanitize')) {
    /**
     * Sanitizar string
     */
    function sanitize($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('format_price')) {
    /**
     * Formatear precio
     */
    function format_price($price, $currency = '$') {
        return $currency . number_format($price, 2);
    }
}

if (!function_exists('format_date')) {
    /**
     * Formatear fecha
     */
    function format_date($date, $format = 'd/m/Y') {
        return date($format, strtotime($date));
    }
}

if (!function_exists('str_slug')) {
    /**
     * Crear slug de string
     */
    function str_slug($string) {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}

if (!function_exists('truncate')) {
    /**
     * Truncar texto
     */
    function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('is_active_route')) {
    /**
     * Verificar si es la ruta activa
     */
    function is_active_route($route) {
        $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
        $currentUri = parse_url($currentUri, PHP_URL_PATH);
        
        return $currentUri === $route;
    }
}

if (!function_exists('generate_random_string')) {
    /**
     * Generar string aleatorio
     */
    function generate_random_string($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}

if (!function_exists('validate_email')) {
    /**
     * Validar email
     */
    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('upload_allowed_mime_map')) {
    /**
     * Mapa extensión permitida -> tipos MIME reales aceptados para esa
     * extensión. Se usa para verificar el contenido real del archivo
     * (magic bytes vía fileinfo), no lo que el navegador dice que es.
     */
    function upload_allowed_mime_map() {
        return [
            'jpg'  => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png'  => ['image/png'],
            'gif'  => ['image/gif'],
            'webp' => ['image/webp'],
        ];
    }
}

if (!function_exists('upload_validate_file')) {
    /**
     * Valida un archivo subido contra una lista blanca de extensiones y
     * el tipo MIME real detectado por su contenido (no por el nombre ni
     * por el Content-Type que envía el cliente, que son trivialmente
     * falsificables). Separado de upload_file() para poder probarlo con
     * un archivo cualquiera en disco, sin pasar por una subida HTTP real.
     *
     * Devuelve la extensión validada (string) en éxito, o false.
     */
    function upload_validate_file($originalName, $tmpPath, $size, $allowedExtensions = null, $maxSize = null) {
        $uploadConfig = config('app.upload', []);
        $allowedExtensions = $allowedExtensions ?? ($uploadConfig['allowed_types'] ?? ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $maxSize = $maxSize ?? ($uploadConfig['max_size'] ?? 10485760);

        if ($size <= 0 || $size > $maxSize) {
            return false;
        }

        if (!is_file($tmpPath)) {
            return false;
        }

        // Solo se acepta la ÚLTIMA extensión (rechaza trucos de doble
        // extensión tipo "shell.php.jpg" a nivel de nombre, aunque la
        // verificación real de contenido de abajo es la que de verdad
        // impide subir un .php disfrazado de imagen).
        $extension = strtolower(pathinfo((string)$originalName, PATHINFO_EXTENSION));

        if ($extension === '' || !in_array($extension, $allowedExtensions, true)) {
            return false;
        }

        $mimeMap = upload_allowed_mime_map();
        if (!isset($mimeMap[$extension])) {
            // Extensión listada en la configuración pero sin mapeo MIME
            // conocido aquí: por seguridad, se rechaza en vez de asumir.
            return false;
        }

        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
        $detectedMime = $finfo ? finfo_file($finfo, $tmpPath) : false;
        // finfo_close() is redundant (and deprecated as of PHP 8.5, since
        // finfo objects are now freed automatically) but still required
        // on the older PHP versions this project targets (php >=7.4).
        if ($finfo && PHP_VERSION_ID < 80500) {
            finfo_close($finfo);
        }

        if (!$detectedMime || !in_array($detectedMime, $mimeMap[$extension], true)) {
            return false;
        }

        return $extension;
    }
}

if (!function_exists('upload_file')) {
    /**
     * Subir archivo de forma segura.
     *
     * A diferencia de la versión original, esto:
     *  - Verifica que el archivo llegó por una subida HTTP real
     *    (is_uploaded_file), no una ruta arbitraria del servidor.
     *  - Valida extensión + tipo MIME real (ver upload_validate_file).
     *  - Genera un nombre completamente nuevo (no reutiliza nada del
     *    nombre original) para evitar path traversal, caracteres
     *    peligrosos o extensiones dobles.
     */
    function upload_file($file, $destination = 'uploads/') {
        if (!isset($file['tmp_name'], $file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $extension = upload_validate_file($file['name'] ?? '', $file['tmp_name'], $file['size'] ?? 0);
        if ($extension === false) {
            return false;
        }

        $uploadPath = __DIR__ . '/../../public/' . rtrim($destination, '/') . '/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;
        $fullPath = $uploadPath . $fileName;

        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return rtrim($destination, '/') . '/' . $fileName;
        }

        return false;
    }
}

if (!function_exists('array_get')) {
    /**
     * Obtener valor de array usando notación de punto
     */
    function array_get($array, $key, $default = null) {
        if (is_null($key)) {
            return $array;
        }
        
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        
        return $array;
    }
}