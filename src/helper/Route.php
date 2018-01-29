<?php
namespace helper;

use controller\User;

class Route
{
    public static $is;

    public function __construct()
    {
        /**
         * 로그인관련 기본 동작
         * $_SESSION['id'] string 로그인 중일 때 존재하는 세션 값
         * array login_check($mb_id) 오늘 처음 로그인인지 확인하고 맴버 전역 변수를 할당
         * 자동로그인 쿠키가 존재 할 때 기본 동작
         * md5로 쿠키 저장 후 서로 일치하는지 검증
         * 서버 IP, 클라이언트 IP, 클라이언트 AGENT, 클라이언트 비밀번호를 해시값으로 사용
         * 쿠키에 저장된 해시값과 현재 해시값이 일치하면 로그인 한 것으로 간주
         * 세션 시작이 제대로 되지 않을 경우 페이지를 다시 리로드
         * @see controller/User.php
         */

        $cf = config();

        $us = new User();
        if (!empty($_SESSION['id'])) {
            $user = $us->getRow('id', $_SESSION['id']);
        } else {
            $user = $us->getAutoLogin();
        }

        if (!empty($user['id'])) {
            $cf['is']['user'] = TRUE;
            if ($user['id'] === '1') {
                $cf['is']['admin'] = TRUE;
            }
        } else {
            $cf['is']['guest'] = TRUE;
        }

        self::$is = $cf['is'];
    }

    public static function template($path, $arguments = NULL, $head = NULL, $ob = NULL)
    {
        header('Content-Type: text/html; charset=utf-8');

        extract(Model::getQueryVars());

        $cf = config();

        if ($arguments !== NULL) {
            extract($arguments);
        }

        if ($ob !== NULL) {
            ob_start();
        }

        if ($head === 'no-header') {
            require_once VIEW.'/template/html-head.php';

        } else if ($head === 'header') {
            require_once VIEW.'/template/html-head.php';
            require_once VIEW.'/template/header.php';
        }

        require_once VIEW.$path.'.php';

        if ($head === 'no-header') {
            require_once VIEW.'/template/body-html.php';

        } else if ($head === 'header') {
            require_once VIEW.'/template/footer.php';
            require_once VIEW.'/template/body-html.php';
        }

        if ($ob !== NULL) {
            $template = ob_get_contents();
            ob_end_clean();

            return $template;
        }

        return;
    }

    public static function api($res = TRUE, $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($res !== TRUE) {
            $data['error'] = $res;
        }
        echo json_encode($data);
        exit;
    }

    /**
     * @brief 페이지를 이동함
     * @param string $url 이동할 페이지 URL
     * @return void
     */
    public static function location($url)
    {
        header('Location: '. str_replace('&amp;', '&', $url), TRUE, 301);
        exit;
    }

    /**
     * 파일 다운로드
     * @authoer hheo (hheo@cozmoworks.com)
     * @param string $filepath
     * @param string $filename
     * @return void
     */
    public static function download($filepath, $filename)
    {
        $filesize = filesize($filepath);
        header("Pragma: public");
        header("Expires: 0");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $filesize");

        readfile($filepath);
        exit;
    }


    public static function get($uri, $callback)
    {
        if (!GET || strpos(URI, $uri) !== 0 || strlen($uri) > strlen(URI)) return;

        $callback(NULL, self::$is);
        exit;
    }

    public static function post($uri, $callback)
    {
        if (!POST || strpos(URI, $uri) !== 0 || strlen($uri) > strlen(URI)) return;

        $callback(NULL, self::$is);
        exit;
    }

    public static function bootstrap()
    {
        self::template('/template/bootstrap', [], 'no-header');
    }

    public static function main()
    {
        self::template('/template/main', [], 'header');
    }
}
