<?php
/**
 * 디렉터리 상수
 */
define('CTRL_DIR', 'controller');
define('VIEW_DIR', 'view');
define('MODEL_DIR', 'model');
define('HELPER_DIR', 'helper');
define('ROOT_DIR', 'public');
define('CSS_DIR', 'css');
define('FONT_DIR', 'fonts');
define('JS_DIR', 'js');
define('IMG_DIR', 'img');
define('UPLOAD_DIR', 'uploads');

define('PATH', __DIR__);
define('CTRL', PATH.'/'.CTRL_DIR);
define('MODEL', PATH.'/'.MODEL_DIR);
define('HELPER', PATH.'/'.HELPER_DIR);
define('VIEW', PATH.'/'.VIEW_DIR);

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('DOMAIN', $_SERVER['HTTP_HOST']);

define('URL', !empty($_SERVER['HTTPS']) ? 'https://' . DOMAIN : 'http://' . DOMAIN);
define('CSS', URL.'/'.CSS_DIR);
define('FONT', URL.'/'.FONT_DIR);
define('JS', URL.'/'.JS_DIR);
define('IMG', URL.'/'.IMG_DIR);
define('UPLOAD', URL.'/'.UPLOAD_DIR);

define('UPLOAD_PATH', ROOT.'/'.UPLOAD_DIR);

define('SKIN', 'skin');

/**
 * 동일한 세션은 동일한 시간을 사용하도록 상수화
 */
define('TIME', time());
define('YMDHIS', date('Y-m-d H:i:s', TIME));
define('YMD', date('Y-m-d', TIME));
define('HIS', date('H:i:s', TIME));


define('GET', $_SERVER['REQUEST_METHOD'] === 'GET');
define('POST', $_SERVER['REQUEST_METHOD'] === 'POST');
define('PUT', $_SERVER['REQUEST_METHOD'] === 'PUT');
define('DELETE', $_SERVER['REQUEST_METHOD'] === 'DELETE');
define('URI', explode('?', $_SERVER['REQUEST_URI'])[0]);
define('URIS', array_map('rawurldecode', array_pad(explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]), 5, null)));
define('MOBILE', preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$_SERVER['HTTP_USER_AGENT']));
define('WINDOWS', preg_match('/windows/i', $_SERVER['HTTP_USER_AGENT']));
