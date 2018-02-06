<?php
/**
 * Route.php
 * 
 * PHP Version 7
 * 
 * @category Helper
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
namespace helper;

use controller\User;

/**
 * Route Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
final class Route
{
    public static $is;

    /**
     * 로그인관련 기본 동작
     * $_SESSION['id'] string 로그인 중일 때 존재하는 세션 값
     * array login_check($mb_id) 오늘 처음 로그인인지 확인하고 맴버 전역 변수를 할당
     * 자동로그인 쿠키가 존재 할 때 기본 동작
     * sha1로 쿠키 저장 후 서로 일치하는지 검증
     * 서버 IP, 클라이언트 IP, 클라이언트 AGENT, 클라이언트 비밀번호를 해시값으로 사용
     * 쿠키에 저장된 해시값과 현재 해시값이 일치하면 로그인 한 것으로 간주
     * 세션 시작이 제대로 되지 않을 경우 페이지를 다시 리로드
     * 
     * @return null
     */
    public function __construct()
    {
        $cf = config();

        $us = new User();
        if (!empty($_SESSION['id'])) {
            $user = $us->getRow('id', $_SESSION['id']);
        } else {
            $user = $us->getAutoLogin();
        }
        $cf['user'] = $user;

        if (!empty($user['id'])) {
            $cf['is']['user'] = true;
            if ($user['id'] === '1') {
                $cf['is']['admin'] = true;
            }
        } else {
            $cf['is']['guest'] = true;
        }

        config($cf);

        self::$is = $cf['is'];

        return null;
    }

    /**
     * 템플릿 출력 함수
     * 
     * @param string $path      스킨 path
     * @param array  $arguments 전달인자
     * @param string $head      head 템플릿 포함여부
     * @param bool   $ob        출력방식
     * 
     * @return string|exit
     */
    public static function template(
        $path, $arguments = null, $head = null, $ob = false
    ) {
        header('Content-Type: text/html; charset=utf-8');

        extract(Model::queryStrings());
        $qstr = Model::queryString();
        $cf = config();

        if ($arguments !== null) {
            extract($arguments);
        }

        if ($ob) {
            ob_start();
        }

        if ($head === 'no-header') {
            include_once VIEW.'/template/html-head.php';

        } else if ($head === 'header') {
            include_once VIEW.'/template/html-head.php';
            include_once VIEW.'/template/header.php';
        }

        include_once VIEW.$path.'.php';

        if ($head === 'no-header') {
            include_once VIEW.'/template/body-html.php';

        } else if ($head === 'header') {
            include_once VIEW.'/template/footer.php';
            include_once VIEW.'/template/body-html.php';
        }

        if ($ob) {
            $template = ob_get_contents();
            ob_end_clean();

            return $template;
        }

        return exit;
    }

    /**
     * API 호출 처리
     * 
     * @param bool|string $res  true의 경우 정상 출력, string의 경우 에러 메시지 출력
     * @param array       $data 테이터
     * 
     * @return exit
     */
    public static function api($res = true, $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($res !== true) {
            $data['error'] = $res;
        }
        echo json_encode($data);

        return exit;
    }

    /**
     * 페이지를 이동함
     * 
     * @param string $url 이동할 페이지 URL
     * 
     * @return exit
     */
    public static function location($url)
    {
        header('Location: '. str_replace('&amp;', '&', $url), true, 301);
        return exit;
    }

    /**
     * 파일 다운로드
     * 
     * @param string $path 다운받을 파일 path
     * @param string $name 다운받을 파일명
     * 
     * @return exit
     */
    public static function download($path, $name)
    {
        $size = filesize($path);
        header("Pragma: public");
        header("Expires: 0");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$name\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $size");

        readfile($path);
        return exit;
    }

    /**
     * GET
     * 
     * @param string    $uri      uri
     * @param callabled $callback callback
     * 
     * @return exit
     */
    public static function get($uri, $callback)
    {
        if (!GET || mb_strpos(URI, $uri) !== 0 || mb_strlen($uri) > mb_strlen(URI)) {
            return;
        }

        $callback(null, self::$is);
        return exit;
    }

    /**
     * POST
     * 
     * @param string    $uri      uri
     * @param callabled $callback callback
     * 
     * @return exit
     */
    public static function post($uri, $callback)
    {
        if (!POST || strpos(URI, $uri) !== 0 || strlen($uri) > strlen(URI)) {
            return;
        }

        $callback(null, self::$is);
        return exit;
    }

    /**
     * Bootstrap 템플릿
     * 
     * @return null
     */
    public static function bootstrap()
    {
        self::template('/template/bootstrap', [], 'no-header');
    }

    /**
     * Main 템플릿
     * 
     * @return null
     */
    public static function main()
    {
        self::template('/template/main', [], 'header');
    }
}
