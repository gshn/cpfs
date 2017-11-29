<?php
/**
 * @file lib.php
 * @brief UNIX 타임스템프를 가져와서 밀리초를 얻음
 * @author hheo (hheo@cozmoworks.com)
 * @return float
 * @see DEV 모드
 */
function microtime_float()
{
    return DEV ? array_sum(explode(' ', microtime())) : null;
}

/**
 * @brief print_r 내장함수를 보기 편리하도록 치환
 * @author hheo (hheo@cozmoworks.com)
 * @param mixed $var 표기할 객체
 * @see print_r()
 */
function printr($var)
{
    if (DEV) {
        echo '<pre>';
        print_r($var);
        echo '<pre>';
    }
}

/**
 * @brief var_dump 내장함수를 보기 편리하도록 치환
 * @author hheo (hheo@cozmoworks.com)
 * @param mixed $var 표기할 객체
 * @see var_dump()
 */
function vardump($var)
{
    if (DEV) {
        echo '<pre>';
        var_dump($var);
        echo '<pre>';
    }
}

/**
 * @brief sweetalert을 이용한 클라이언트 측 경고창
 * @author hheo (hheo@cozmoworks.com)
 * @param string $type primary, info, success, warning, danger 타입별 스타일
 * @param string $title 경고 타이틀
 * @param string $text 경고할 내용
 * @param string $url 경고후 이동할 URL
 * @return void
 * @see https://lipis.github.io/bootstrap-sweetalert/
 * @see /app/view/template/swal.php
 */
function swal($title = '확인!', $text = '올바른 방법으로 이용해주세요.', $type = 'warning', $url = null)
{
    require VIEW.'/template/swal.php';
    exit;
}

/**
 * @brief 페이지를 이동함
 * @author hheo (hheo@cozmoworks.com)
 * @param string $url 이동할 페이지 URL
 * @return void
 */
function location(string $url)
{
    header('Location: '. str_replace('&amp;', '&', $url));
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
            $html .= '<link rel="stylesheet" href="'.$url.'?'.filemtime(ROOT.$url).'">'.PHP_EOL;
        }
    }

    return $html;
}

function api_result($res = 200, $data = null)
{
    if ($res !== 200) {
        $data['res'] = $res;

        echo json_encode($data);

    } else {
        $data['res'] = 200;

        echo json_encode($data);

    }
    exit;
}

function template($path, $dataset = null, $head = null)
{
    extract($dataset);

    if ($head === 'no-header') {
        require_once VIEW.'/template/html-head.php';
    } else {
        require_once VIEW.'/template/header.php';
    }
    require_once VIEW.$path.'.php';
    if ($head === 'no-header') {
        require_once VIEW.'/template/body-html.php';
    } else {
        require_once VIEW.'/template/footer.php';
    }
}
