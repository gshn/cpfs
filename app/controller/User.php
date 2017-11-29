<?php
namespace controller;

use model\UserModel;
use Facebook\Facebook;
use helper\Library;

class User extends UserModel
{
    private static function _password($password)
    {
        $sql = 'SELECT PASSWORD(?) AS password';
        $rst = self::$pdo->query($sql, [$password]);
        $password = $rst->fetchColumn();

        return $password;
    }

    private static function _setNickname($nickname)
    {
        if (empty($nickname)) {
            $nickname = '동고물';
        }

        $num = 1;

        $sql = "SELECT COUNT(*) AS cnt
                FROM tbluser
                WHERE nickname = '{$nickname}'";
        $cnt = self::$pdo->query($sql)->fetchColumn();
        if ($cnt > 0) {
            $sql = "SELECT nickname
                    FROM tbluser
                    WHERE nickname LIKE '{$nickname}#%'
                    ORDER BY nickname DESC
                    LIMIT 0, 1";
            $nickcount = self::$pdo->query($sql)->fetchColumn();
            if (!empty($nickcount)) {
                $num = explode('#', $nickcount)[1] + 1;
            }

            return $nickname.'#'.$num;

        } else {
            return $nickname;
        }
    }

    private static function _hash($password)
    {
        return md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$password);
    }

    private static function _setAutoLogin($user = null)
    {
        global $pdo;

        if ($user === null) {
            Library::set_cookie('id', null);
            Library::set_cookie('autoLogin', null);
        } else if (is_array($user)) {
            $hash = self::_hash($user['login_pw']);
            Library::set_cookie('id', $user['id']);
            Library::set_cookie('autoLogin', $hash);
        }
    }

    public function getAutoLogin()
    {
        $id = (int)Library::get_cookie('id');
        if (!empty($id)) {
            $user = $this->getRow('id', $id);
            $hash_key = self::_hash($user['login_pw']);
            $hash = Library::get_cookie('autoLogin');
            if (!empty($hash) && $hash === $hash_key) {
                if ($user['hidden'] === 'n') {
                    $_SESSION['id'] = $id;
                    return $user;
                }
            }
            self::_setAutoLogin(null);
        }

        return [];
    }

    public function login($package_name)
    {
        extract(self::_getVars($filters = [
            'login_id' => FILTER_SANITIZE_STRING,
            'login_pw' => FILTER_UNSAFE_RAW,
            'auto' => FILTER_VALIDATE_BOOLEAN
        ]));

        if (empty($package_name)) {
            $package_name = 'normal';
        }

        if ($package_name !== 'normal' && $package_name !== 'company') {
            swal('에러!', '잘못된 접근입니다.', 'error', '/');
        }

        $user = $this->getRow('login_id', $login_id, $package_name);

        if (empty($user['id']) || ($user['login_pw'] !== self::_password($login_pw))) {
            swal('실패!', '가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.', 'warning');
        }

        if ($user['hidden'] === 'y') {
            $date = date('y년 m월 d일', strtotime($user['delete_date']));
            swal('실패!', '탈퇴한 아이디이므로 접근하실 수 없습니다.\\n탈퇴일 : '.$date, 'warning');
        }

        $_SESSION['id'] = $user['id'];

        if ($auto !== null) {
            self::_setAutoLogin($user);
        } else {
            self::_setAutoLogin(null);
        }

        location('/');
    }

    public static function logout()
    {
        session_unset();
        session_destroy();

        self::_setAutoLogin(null);

        location('/');
    }

    public function formLogin($package_name)
    {
        if ($package_name !== 'company') {
            $package_name = 'normal';
            $package = '';
            $naver_client_id = NORMAL_NAVER_CLIENT_ID;
            $naver_redirect_uri = NORMAL_NAVER_REDIRECT_URI;
            $facebook_app_id = NORMAL_FACEBOOK_APP_ID;
            $facebook_app_secret = NORMAL_FACEBOOK_APP_SECRET;
            $facebook_redirect_uri = NORMAL_FACEBOOK_REDIRECT_URI;
            $kakao_api_key = NORMAL_KAKAO_API_KEY;
            $kakao_redirect_uri = NORMAL_KAKAO_REDIRECT_URI;
        } else {
            $package = '기업';
            $naver_client_id = COMPANY_NAVER_CLIENT_ID;
            $naver_redirect_uri = COMPANY_NAVER_REDIRECT_URI;
            $facebook_app_id = COMPANY_FACEBOOK_APP_ID;
            $facebook_app_secret = COMPANY_FACEBOOK_APP_SECRET;
            $facebook_redirect_uri = COMPANY_FACEBOOK_REDIRECT_URI;
            $kakao_api_key = COMPANY_KAKAO_API_KEY;
            $kakao_redirect_uri = COMPANY_KAKAO_REDIRECT_URI;
        }

        // 네이버 로그인
        $state = Library::setState();
        $naver_login_uri = 'https://nid.naver.com/oauth2.0/authorize?client_id='.$naver_client_id.'&response_type=code&redirect_uri='.urlencode($naver_redirect_uri).'&state='.$state;

        // 페이스북 로그인
        $fb = new Facebook([
            'app_id' => $facebook_app_id,
            'app_secret' => $facebook_app_secret,
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email', 'public_profile'];
        $loginUrl = $helper->getLoginUrl($facebook_redirect_uri, $permissions);
        $facebook_login_uri = htmlspecialchars($loginUrl);

        // 카카오 로그인
        $kakao_login_uri = 'https://kauth.kakao.com/oauth/authorize?client_id='.$kakao_api_key.'&redirect_uri='.$kakao_redirect_uri.'&response_type=code&state='.$state;

        require VIEW.'/template/html-head.php';
        require VIEW.'/user/login.php';
        require VIEW.'/template/body-html.php';
    }

    public function register($package_name)
    {
        $vars = self::_getVars($filters = [
            'account_type' => FILTER_SANITIZE_STRING,
            'login_id' => FILTER_SANITIZE_STRING,
            'login_pw' => FILTER_UNSAFE_RAW,
            'email' => FILTER_VALIDATE_EMAIL,
            'nickname' => FILTER_SANITIZE_STRING,
            'hp' => FILTER_SANITIZE_STRING,
            'postzip' => FILTER_SANITIZE_STRING,
            'addr_01' => FILTER_SANITIZE_STRING,
            'addr_02' => FILTER_SANITIZE_STRING
        ]);
        extract($vars);

        if (empty($account_type)) {
            $vars['account_type'] = 'normal';
        }

        if ($account_type !== 'normal' && $account_type !== 'naver' && $account_type !== 'facebook' && $account_type !== 'kakao') {
            swal('에러!', '잘못된 접근입니다.', 'error', '/');
        }

        if (empty($package_name)) {
            $package_name = 'normal';
        }

        if ($package_name !== 'normal' && $package_name !== 'company') {
            swal('에러!', '잘못된 접근입니다.', 'error', '/');
        }

        $vars['package_name'] = $package_name;

        if (empty($login_id) || empty($login_pw)) {
            swal('에러!', '잘못된 접근입니다.', 'error', '/');
        }

        $vars['login_pw'] = self::_password($login_pw);

        if ($this->getRow('login_id', $login_id, $package_name)) {
            swal('아이디 중복!', '사용중인 아이디입니다. 다른 아이디를 입력해주세요.', 'warning');
        }

        if ($this->getRow('email', $email, $package_name)) {
            swal('이메일 중복!', '사용중인 이메일입니다. 다른 이메일을 입력해주세요.', 'warning');
        }

        if ($this->getRow('nickname', $nickname)) {
            swal('닉네임 중복!', '사용중인 닉네임입니다. 다른 닉네임을 입력해주세요.', 'warning');
        }

        if (!empty($hp)) {
            if (Library::valid_hp_number($hp)) {
                swal('오류!', '휴대전화번호를 정확하게 입력해주세요.', 'warning');
            }
            $vars['hp'] = Library::get_hp_number($hp);
        }



        if ($this->insert($vars)) {
            $this->login($package_name);
        }
    }

    public function formRegister($package_name)
    {
        define('REGISTER', TRUE);

        if ($package_name !== 'company') {
            $package_name = 'normal';
            $package = '';
        } else {
            $package = '기업';
        }

        require VIEW.'/template/html-head.php';
        require VIEW.'/user/register.php';
        require VIEW.'/template/body-html.php';
    }

    public function kakaoLogin($package_name)
    {
        if ($package_name !== 'company') {
            $package_name = 'normal';
            $kakao_api_key = NORMAL_KAKAO_API_KEY;
            $kakao_redirect_uri = NORMAL_KAKAO_REDIRECT_URI;
        } else {
            $kakao_api_key = COMPANY_KAKAO_API_KEY;
            $kakao_redirect_uri = COMPANY_KAKAO_REDIRECT_URI;
        }

        $state = $_GET['state'] ?? '';
        $code = $_GET['code'] ?? '';
        if ($state !== $_SESSION['state']) {
            swal('오류!', '브라우저 쿠키를 허용해주세요.', 'error');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://kauth.kakao.com/oauth/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id='.$kakao_api_key.'&redirect_uri='.$kakao_redirect_uri.'&code='.$code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $token = json_decode(curl_exec($ch));
        curl_close($ch);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://kapi.kakao.com/v1/user/me');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.$token->token_type.' '.$token->access_token));

        $rst = json_decode(curl_exec($ch));
        curl_close($ch);

        $me = (array)$rst;
        $me['nickname'] = $me['properties']->nickname;

        $id = $me['id'] ?? rand(100000, 999999);

        $_REQUEST = [
            'account_type' => 'kakao',
            'package_name' => $package_name,
            'login_id' => $id,
            'login_pw' => $id
        ];

        if ($this->getRow('login_id', $id, $package_name)) {
            $this->login($package_name);

        } else {
            $_REQUEST['email'] = $me['kaccount_email'] ?? null;
            $_REQUEST['nickname'] = self::_setNickname($me['nickname']);

            $this->register($package_name);
        }
    }

    public function facebookLogin($package_name)
    {
        if ($package_name !== 'company') {
            $package_name = 'normal';
            $facebook_app_id = NORMAL_FACEBOOK_APP_ID;
            $facebook_app_secret = NORMAL_FACEBOOK_APP_SECRET;
            $facebook_redirect_uri = NORMAL_FACEBOOK_REDIRECT_URI;
        } else {
            $facebook_app_id = COMPANY_FACEBOOK_APP_ID;
            $facebook_app_secret = COMPANY_FACEBOOK_APP_SECRET;
            $facebook_redirect_uri = COMPANY_FACEBOOK_REDIRECT_URI;
        }

        $fb = new Facebook([
            'app_id' => $facebook_app_id,
            'app_secret' => $facebook_app_secret,
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            swal('오류!', 'Graph returned an error: ' . $e->getMessage(), 'error');
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            swal('오류!', 'Facebook SDK returned an error: ' . $e->getMessage(), 'error');
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name', $accessToken);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();

        $id = $me['id'] ?? rand(100000, 999999);

        $_REQUEST = [
            'account_type' => 'facebook',
            'package_name' => $package_name,
            'login_id' => $id,
            'login_pw' => $id
        ];

        if ($this->getRow('login_id', $id, $package_name)) {
            $this->login($package_name);

        } else {
            $_REQUEST['email'] = $me['email'] ?? null;
            $_REQUEST['nickname'] = self::_setNickname($me['name']);

            $this->register($package_name);
        }
    }

    public function naverLogin($package_name)
    {
        if ($package_name !== 'company') {
            $package_name = 'normal';
            $naver_client_id = NORMAL_NAVER_CLIENT_ID;
            $naver_client_secret = NORMAL_NAVER_CLINET_SECRET;
        } else {
            $naver_client_id = COMPANY_NAVER_CLIENT_ID;
            $naver_client_secret = COMPANY_NAVER_CLINET_SECRET;
        }

        $state = $_GET['state'] ?? '';
        $code = $_GET['code'] ?? '';
        if ($state !== $_SESSION['state']) {
            swal('오류!', '브라우저 쿠키를 허용해주세요.', 'error');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://nid.naver.com/oauth2.0/token?client_id='.$naver_client_id.'&client_secret='.$naver_client_secret.'&grant_type=authorization_code&state='.$state.'&code='.$code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $token = json_decode(curl_exec($ch));
        curl_close($ch);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://openapi.naver.com/v1/nid/me');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.$token->token_type.' '.$token->access_token));

        $rst = json_decode(curl_exec($ch));
        curl_close($ch);

        $me = (array)$rst->response;

        $id = $me['id'] ?? rand(100000, 999999);

        $_REQUEST = [
            'account_type' => 'naver',
            'package_name' => $package_name,
            'login_id' => $id,
            'login_pw' => $id
        ];

        if ($this->getRow('login_id', $id, $package_name)) {
            $this->login($package_name);

        } else {
            $_REQUEST['email'] = $me['email'] ?? null;
            $_REQUEST['nickname'] = self::_setNickname($me['nickname']);

            $this->register($package_name);
        }
    }

    public function formForgot($package_name)
    {
        if ($package_name !== 'company') {
            $package_name = 'normal';
            $package = '';
        } else {
            $package = '기업';
        }

        require VIEW.'/template/html-head.php';
        require VIEW.'/user/forgot.php';
        require VIEW.'/template/body-html.php';
    }
}
