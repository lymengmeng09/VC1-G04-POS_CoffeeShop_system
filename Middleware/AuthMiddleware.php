<?php
// Middleware/AuthMiddleware.php
require_once 'Helpers/AccessControl.php';

class AuthMiddleware {
    /**
     * Check if user is authenticated
     */
    public static function authenticate() {
        if (!AccessControl::isLoggedIn()) {
            header('Location: /login');
            exit();
        }
    }
    
    /**
     * Check if user has specific permission
     * 
     * @param string $permission
     */
    public static function checkPermission($permission) {
        self::authenticate(); // First ensure user is logged in
        
        if (!AccessControl::hasPermission($permission)) {
            // Redirect to unauthorized page or dashboard with error
            header('Location: /?error=unauthorized');
            exit();
        }
    }
}
