<?php
class Route
{
    public static $uri;

    public function __construct()
    {
        self::$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    public static function get($uri, $method = null)
    {
        if (self::$uri === $uri) {
            $uris = explode('/', $uri);
            if (!empty($uris[1])) {
                $className = $uris[1];
                $$className = new $uris[1]();
            }

            if (is_callable($method, true, $called)) {
                $method($$className);
            }
        }
    }

    public static function post($uri, $method = null)
    {
        if (self::$uri === $uri && count($_POST) > 0) {
            if (is_callable($method, true, $called)) {
                $method();
            }
        }
    }

    public static function template($path, $head = null)
    {
        global $cf;
        if ($head === 'no-header') {
            require_once VIEW.'/template/html-head.php';
        } else {
            require_once VIEW.'/template/header.php';
        }
        require_once $path;
        if ($head === 'no-header') {
            require_once VIEW.'/template/body-html.php';
        } else {
            require_once VIEW.'/template/footer.php';
        }
    }
}
