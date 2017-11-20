<?php
/**
 * @file /app/config.php
 * @author hheo (hheo@cozmoworks.com)
 * 전역으로 사용되는 상수와 변수를 관리함
 * @see /app.php 공통 수행 스크립트
 * @see /app/lib.php 공통 함수
 * @see /app/route.php URI 라우트 설정
 */

/**
 * 디렉터리 설정 및 쿠키 도메인 설정
 */
if (!file_exists(__DIR__.'/app.php')) {
    echo 'Change the app.conf file name to app.php.';
    exit;
}
require __DIR__.'/app.php';

define('PATH', __DIR__);
define('CTRL', PATH.'/'.CTRL_DIR);
define('MODEL', PATH.'/'.MODEL_DIR);
define('HELPER', PATH.'/'.HELPER_DIR);
define('VIEW', PATH.'/'.VIEW_DIR);

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('DOMAIN', $_SERVER['HTTP_HOST']);

define('URL', $_SERVER['HTTPS'] ? 'https://' . DOMAIN : 'http://' . DOMAIN);
define('CSS', URL.'/'.CSS_DIR);
define('FONT', URL.'/'.FONT_DIR);
define('JS', URL.'/'.JS_DIR);
define('IMG', URL.'/'.IMG_DIR);

define('UPLOAD_PATH', ROOT.'/'.UPLOAD_DIR);
define('UPLOAD_URL', URL.'/'.UPLOAD_DIR);

/**
 * 동일한 세션은 동일한 시간을 사용하도록 상수화
 */
define('TIME', time());
define('YMDHIS', date('Y-m-d H:i:s', TIME));
define('YMD', date('Y-m-d', TIME));
define('HIS', date('H:i:s', TIME));

/**
 * 모델 Auto Loader
 */
function modelLoader($class) {
    if (strpos($class, 'Helper') !== false || strpos($class, 'Trait') !== false) {
        require HELPER.'/'.$class.'.php';
    } else if (strpos($class, 'Model') !== false) {
        require MODEL.'/'.$class.'.php';
    } else {
        require CTRL.'/'.strtolower($class).'/'.$class.'.php';
    }
}
spl_autoload_register('modelLoader');
