<?php
/**
 * Session Management Class
 */

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        session_destroy();
        $_SESSION = [];
    }

    public static function isLoggedIn() {
        return self::has('user_id');
    }

    public static function getUserId() {
        return self::get('user_id');
    }

    public static function setUser($user_id, $email, $role) {
        self::set('user_id', $user_id);
        self::set('email', $email);
        self::set('role', $role);
    }
}
