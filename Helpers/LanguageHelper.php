<?php

class LanguageHelper {
    private static $translations = [];
    private static $defaultLang = 'en';
    private static $supportedLangs = ['en', 'km'];
    private static $cookieName = 'site_language';
    private static $cookieExpiry = 2592000; // 30 days in seconds
    
    /**
     * Initialize the language system
     */
    /**
 * Get JavaScript validation messages
 */
public static function getJsValidationMessages() {
    return [
        'email_required' => self::translate('email_required'),
        'password_required' => self::translate('password_required'),
        'field_required' => self::translate('field_required'),
        'valid_email' => self::translate('valid_email'),
        'name_required' => self::translate('name_required'),
        'role_required' => self::translate('role_required'),
        'password_length' => self::translate('password_length'),
        'confirm_password' => self::translate('confirm_password'),
        'passwords_not_match' => self::translate('passwords_not_match'),
        'email_not_registered' => self::translate('email_not_registered')
    ];
}
    public static function init() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check for language in cookie first
        $cookieLang = isset($_COOKIE[self::$cookieName]) ? $_COOKIE[self::$cookieName] : null;
        
        // Set default language if not set in session or cookie
        if (!isset($_SESSION['site_lang'])) {
            $_SESSION['site_lang'] = $cookieLang ?: self::$defaultLang;
        }
        
        // Handle language change request
        if (isset($_GET['lang']) && in_array($_GET['lang'], self::$supportedLangs)) {
            $newLang = $_GET['lang'];
            $_SESSION['site_lang'] = $newLang;
            
            // Set cookie to persist language preference
            setcookie(self::$cookieName, $newLang, time() + self::$cookieExpiry, '/');
            
            // Redirect to remove the 'lang' parameter from URL
            $redirectUrl = strtok($_SERVER['REQUEST_URI'], '?');
            $queryParams = $_GET;
            unset($queryParams['lang']);
            
            if (!empty($queryParams)) {
                $redirectUrl .= '?' . http_build_query($queryParams);
            }
            
            header('Location: ' . $redirectUrl);
            exit;
        }
        
        // Load translations for current language
        self::loadTranslations($_SESSION['site_lang']);
    }
    
    /**
     * Load translations from file
     */
    private static function loadTranslations($lang) {
        $langFile = __DIR__ . '/../languages/' . $lang . '.php';
        
        if (file_exists($langFile)) {
            self::$translations = include $langFile;
        } else {
            // Fallback to default language
            $defaultLangFile = __DIR__ . '/../languages/' . self::$defaultLang . '.php';
            if (file_exists($defaultLangFile)) {
                self::$translations = include $defaultLangFile;
            }
        }
    }
    
    /**
     * Get translation for a key
     */
    public static function translate($key, $placeholders = []) {
        if (isset(self::$translations[$key])) {
            $text = self::$translations[$key];
            
            // Replace placeholders if any
            foreach ($placeholders as $placeholder => $value) {
                $text = str_replace('{' . $placeholder . '}', $value, $text);
            }
            
            return $text;
        }
        
        // Return the key if translation not found
        return $key;
    }
    
    /**
     * Get current language
     */
    public static function getCurrentLang() {
        return $_SESSION['site_lang'] ?? self::$defaultLang;
    }
    
    /**
     * Get supported languages
     */
    public static function getSupportedLangs() {
        return self::$supportedLangs;
    }
    
    /**
     * Get language name
     */
    public static function getLanguageName($code) {
        $names = [
            'en' => 'English',
            'km' => 'ភាសាខ្មែរ'
        ];
        return $names[$code] ?? $code;
    }
    
    /**
     * Get language flag icon
     */
    public static function getLanguageFlag($code) {
        $flags = [
            'en' => 'us',
            'km' => 'kh'
        ];
        return $flags[$code] ?? $code;
    }
}

// Helper function for easier translation in views
function __($key, $placeholders = []) {
    return LanguageHelper::translate($key, $placeholders);
}

