<?php
/**
 * Config core.php
 * 해당 프레임웍이 실행될 때 마다 수행하는 코어 스크립트
 * Autoloader PSR-4
 * 세션 초기화 및 세션 기본 설정
 * HTTP 캐시 해더 설정을 처리함
 * 
 * PHP Version 7
 * 
 * @category Core
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */

/**
 * 상수 및 자주사용하는 함수
 * 클래스 형태로 이용하지 않고 바로 접근해서 사용할 수 있다.
 */
require __DIR__.'/../src/constant.php';
require PATH.'/lib.php';

/**
 * An example of a project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 * 
 * @return void
 */
spl_autoload_register(
    function ($class) {

        // project-specific namespace prefix
        $prefix = '';

        // base directory for the namespace prefix
        $base_dir = PATH . '/';

        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            include $file;
        }
    }
);

/**
 * 세션 관련 설정
 * session.cache_expire 세션 캐쉬 보관시간 (분)
 * session.gc_maxlifetime 세션 데이터의 가비지 콜렉션 존재 기간을 지정 (초)
 * 세션 유지 시간을 늘리기 위해서는 위의 설정 값을 원하는 기간만큼 늘려주면 됨
 */
ini_set('session.cache_expire', 1800);
ini_set('session.gc_maxlifetime', 43200);
session_set_cookie_params(43200, '/', DOMAIN, !empty($_SERVER['HTTPS']) ? true : false, !empty($_SERVER['HTTPS']) ? true : false);
session_start();

/**
 * 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
 * 캐쉬의 내용을 가져옴. 익스에서 지원하지 않음.
 */
header('Expires: 0');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Pragma: no-cache');

require PATH.'/route.php';
