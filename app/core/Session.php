<?php

class Session
{
    private static $started = false;

    public static function start()
    {
        if (!self::$started) {
            session_start();
            self::$started = true;
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        self::start();
        session_destroy();
        self::$started = false;
    }

    public static function flash($key, $value = null)
    {
        self::start();
        
        if ($value !== null) {
            // Establecer mensaje flash
            $_SESSION['_flash'][$key] = $value;
        } else {
            // Obtener mensaje flash
            $message = $_SESSION['_flash'][$key] ?? null;
            if ($message) {
                unset($_SESSION['_flash'][$key]);
            }
            return $message;
        }
    }

    public static function hasFlash($key)
    {
        self::start();
        return isset($_SESSION['_flash'][$key]);
    }

    public static function generateCsrf()
    {
        self::start();
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function getCsrf()
    {
        self::start();
        return $_SESSION['csrf_token'] ?? self::generateCsrf();
    }

    public static function validateCsrf($token)
    {
        self::start();

        // hash_equals() requires a string for both arguments since PHP 8;
        // a request that omits the CSRF token entirely (very much the
        // expected case for an attacker, or for a broken client) passes
        // null here, which previously caused an uncaught TypeError
        // instead of a clean "invalid token" rejection.
        if (!is_string($token)) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    public static function regenerate()
    {
        self::start();
        session_regenerate_id(true);
    }

    // Métodos para autenticación
    public static function login($userId, $userData = [])
    {
        self::set('user_id', $userId);
        self::set('user_data', $userData);
        self::set('authenticated', true);
        self::regenerate();
    }

    public static function logout()
    {
        self::remove('user_id');
        self::remove('user_data');
        self::remove('authenticated');
        self::destroy();
    }

    public static function isAuthenticated()
    {
        return self::get('authenticated', false);
    }

    public static function getUserId()
    {
        return self::get('user_id');
    }

    public static function getUserData($key = null)
    {
        $userData = self::get('user_data', []);
        return $key ? ($userData[$key] ?? null) : $userData;
    }
}