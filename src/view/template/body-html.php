<?php
/**
 * Template body-html.php
 * 
 * PHP Version 7
 * 
 * @category View
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
?>
<noscript id="deferred-styles">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</noscript>
<?php
echo js_css([
    '/js/jquery-3.2.1/jquery.min.js',
    '/js/jquery-3.2.1/jquery.easing.min.js',
    '/js/bootstrap-4.0.0/js/bootstrap.min.js',
    '/js/sweetalert-1.1.3/sweetalert.min.js',
    '/js/sweetalert-1.1.3/sweetalert.min.css'
]);

echo defined('CKEDITOR') ? js_css([
    '/js/ckeditor-4.8/ckeditor.js'
]) : '';

echo js_css('/js/app.js');

/**
 * 개발 모드일 경우 전역변수들 디버깅
 */
if (DEV) {
    echo '<div hidden>'.PHP_EOL;
    if (GET) {
        echo '$_GET'.PHP_EOL;
        print_r($_GET);
    }
    if (POST) {
        echo '$_POST'.PHP_EOL;
        print_r($_POST);
    }
    if ($_FILES) {
        echo '$_FILES'.PHP_EOL;
        print_r($_FILES);
    }
    if ($_SERVER) {
        echo '$_SERVER'.PHP_EOL;
        print_r($_SERVER);
    }
    if ($_COOKIE) {
        echo '$_COOKIE'.PHP_EOL;
        print_r($_COOKIE);
    }
    if (!empty($_SESSION)) {
        echo '$_SESSION'.PHP_EOL;
        print_r($_SESSION);
    }
    echo 'In Time'.PHP_EOL;
    echo $cf['debug']['query_log'].PHP_EOL;
    echo 'Total SQL Query Time '.round($cf['debug']['query_time'], 4).PHP_EOL;

    echo 'Page rendered in '.round(microtime_float() - $_SERVER['REQUEST_TIME_FLOAT'], 4).PHP_EOL;
    echo '</div>';
}
?>
</body>
</html>
