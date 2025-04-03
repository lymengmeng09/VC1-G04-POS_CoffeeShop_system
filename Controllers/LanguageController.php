<?php
class LanguageController {
    public function change() {
        // Get the selected language from the POST request
        $language = $_POST['language'] ?? 'en';

        // Validate the language
        $supportedLanguages = ['en', 'km'];
        if (!in_array($language, $supportedLanguages)) {
            $language = 'en';
        }

        // Since we're using localStorage, the actual persistence is handled client-side
        // This controller can be extended later for server-side persistence (e.g., session or DB)

        // Redirect back to the previous page (or a default page)
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit;
    }
}