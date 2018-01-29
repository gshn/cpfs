<?php
/**
 * @file lib.php
 * @brief UNIX 타임스템프를 가져와서 밀리초를 얻음
 * @return float
 * @see DEV 모드
 */
function microtime_float()
{
    return DEV ? array_sum(explode(' ', microtime())) : null;
}

/**
 * @brief print_r 내장함수를 보기 편리하도록 치환
 * @param mixed $var 표기할 객체
 * @see print_r()
 */
function printr($var)
{
    if (DEV) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

/**
 * @brief var_dump 내장함수를 보기 편리하도록 치환
 * @param mixed $var 표기할 객체
 * @see var_dump()
 */
function vardump($var)
{
    if (DEV) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

/**
 * @brief sweetalert을 이용한 클라이언트 측 경고창
 * @param string $type primary, info, success, warning, danger 타입별 스타일
 * @param string $title 경고 타이틀
 * @param string $text 경고할 내용
 * @param string $url 경고후 이동할 URL
 * @return void
 * @see https://lipis.github.io/bootstrap-sweetalert/
 * @see /app/view/template/swal.php
 */
function swal($title = '오류!', $text = '올바른 방법으로 이용해주세요.', $type = 'error', $url = null)
{
    global $cf;

    require VIEW.'/template/swal.php';
    exit;
}

function confirm($title = '오류!', $text = '올바른 방법으로 이용해주세요.', $type = 'error', $url = null)
{
    global $cf;

    require VIEW.'/template/confirm.php';
    exit;
}

function js_css($urls)
{
    $html = null;

    if (!is_array($urls)) {
        $urls = (array)$urls;
    }

    foreach ($urls as $url) {
        $arr = explode('.', $url);
        $ext = $arr[count($arr) - 1];

        if ($ext === 'js') {
            $html .= '<script src="'.$url.'?'.filemtime(ROOT.$url).'"></script>'.PHP_EOL;
        } else if ($ext === 'css') {
            $html .= '<link href="'.$url.'?'.filemtime(ROOT.$url).'" rel="stylesheet">'.PHP_EOL;
        }
    }

    return $html;
}

function config($config = null)
{
    global $cf;

    if (!empty($config)) {
        $cf = $config;
    }

    return $cf;
}
