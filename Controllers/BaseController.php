<?php

class BaseController
{
    /**
     * Helper function to render a view.
     *
     * @param string $view The view file to render.
     * @param array $data The data to pass to the view.
     */
    protected function view($view, $data = [])
    {
        extract($data);
        ob_start();
        require "views/{$view}.php";
        $content = ob_get_clean();
        require "views/layout.php";
    }

    /**
     * Helper function to handle redirections.
     *
     * @param string $url The URL to redirect to.
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}