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
 * 개발모드 관련 상수
 * SQL_SLOW_TIME 밀리초보다 더 걸리는 쿼리를 디버그창에 표기
 * DEV 모드일 경우 모든 에러 리포트 및 사용중인 변수들을 앱 하단에 노출
 * @see /static/index.php
 * @see /app/lib.php sql_query()
 */
if ($_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR']) {
    define('DEV', TRUE);
    define('LOG', FALSE);
} else {
    define('DEV', FALSE);
    define('LOG', TRUE);
}
define('SQL_SLOW_TIME', 0.005);

if (DEV) {
    error_reporting(-1);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
}

/**
 * 디렉터리 설정 및 쿠키 도메인 설정
 */
require __DIR__.'/app.php';

define('PATH', __DIR__);
define('CTLR', PATH.'/'.CTLR_DIR);
define('MODEL', PATH.'/'.MODEL_DIR);
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
 * 전역 변수 선언
 * $pdo object DB 커넥터 객체
 * $cz array DB 테이블 명 등 여러가지 변수를 전역적으로 만들어서 갖고 있음
 * $is 각종 설정정보를 true, false 로 저장
 * $uri route.php 에서 사용할 전역 변수
 * rendered_time, query_time, sql_debug DEV 모드에서 사용하는 변수
 * @see /app/common.php
 * @see /app/lib.php array login_check()
 * @see /app/route.php
 */
$pdo = $cz = $member = [];
$cf['query_time'] = 0;
$cf['query_debug'] = null;
$cf['is_mobile'] = $cf['is_webview'] = $cf['is_windows'] = false;

$is_guest = $is_member = $is_admin = false;

$uris = explode('/', $_SERVER['REQUEST_URI']);
$uris = array_pad($uris, 5, null);

/**
 * 모델 Auto Loader
 */
function modelLoader($class) {
    require MODEL.'/'.$class.'.model.php';
}
spl_autoload_register('modelLoader');
