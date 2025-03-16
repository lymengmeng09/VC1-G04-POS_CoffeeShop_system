<?php
// Helpers/AccessControl.php

class AccessControl {
    // Define role names instead of IDs
    const ADMIN_ROLE = 'Admin'; 
    const STAFF_ROLE = 'Staff'; 
    
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['user']);
    }
    
    /**
     * Get current user's role name
     * 
     * @return string|null
     */
    public static function getUserRole() {
        self::startSession();
        // Get the role_name from session
        return $_SESSION['user']['role_name'] ?? null;
    }
    
    /**
     * Check if user is admin
     * 
     * @return bool
     */
    public static function isAdmin() {
        $roleName = self::getUserRole();
        return $roleName == self::ADMIN_ROLE;
    }
    
    /**
     * Check if user is staff
     * 
     * @return bool
     */
    public static function isStaff() {
        $roleName = self::getUserRole();
        return $roleName == self::STAFF_ROLE;
    }
    
    /**
     * Check if user has permission for a specific action
     * 
     * @param string $permission
     * @return bool
     */
    public static function hasPermission($permission) {
        // If not logged in, no permissions
        if (!self::isLoggedIn()) {
            return false;
        }
        
        // Admins have all permissions
        if (self::isAdmin()) {
            return true;
        }
        
        // Define permissions for staff
        $staffPermissions = [
            'view_dashboard' => true,
            'view_products' => true,
            // 'manage_products' => true,
            'view_users' => true,
            'create_users' => false, // Staff cannot create users
            'edit_users' => false,   // Staff cannot edit users
            'delete_users' => false, // Staff cannot delete users
            'access_settings' => false, // Staff cannot access settings
        ];
        
        // Return permission value or false if not defined
        return $staffPermissions[$permission] ?? false;
    }
    
    /**
     * Helper method to safely start a session
     */
    private static function startSession() {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }
}