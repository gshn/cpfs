<?php
class Member extends HelperList
{
    protected $id;
    protected $email;
    protected $hp;
    protected $name;
    protected $password;
    protected $datetime;
    protected $timestamp;
    protected $leavedate;

    public function __construct()
    {
        parent::__construct();
    }

    private function _password($password) {
        return crypt($password, BLOWFISH);
    }

    private static function _setAutoLogin($member = null)
    {
        global $pdo;

        if ($member === null) {
            set_cookie('ck_mb_id', null);
            set_cookie('ck_auto', null);
        } else if (is_array($member)) {
            $hash = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$member['password']);

            set_cookie('id', $member['id']);
            set_cookie('autoLogin', $hash);
        }
    }

    public function getAutoLogin()
    {
        $id = (int)get_cookie('id');
        if (!empty($id)) {
            $member = self::getRow('id', $id);
            $hash_key = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$member['password']);
            $hash = get_cookie('autoLogin');
            if (!empty($hash) && $hash === $hash_key) {
                if ($member['isdelete'] !== null) {
                    $_SESSION['id'] = $id;
                    return $member;
                }
            }
        }

        return [];
    }

    public function login($email, $password, $auto = null)
    {
        $member = self::getRow('email', $email);

        if (empty($member['id']) || ($member['password'] !== self::_password($password))) {
            swal('실패!', '가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.', 'warning');
        }

        if ($member['isdelete'] !== null) {
            $date = date('y년 m월 d일', strtotime($member['timestamp']));
            swal('실패!', '탈퇴한 아이디이므로 접근하실 수 없습니다.\\n탈퇴일 : '.$date, 'warning');
        }

        $_SESSION['id'] = $member['id'];

        if ($auto !== null) {
            self::_setAutoLogin($member);
        } else {
            self::_setAutoLogin(null);
        }

        location('/');
    }

    public function logout() {
        session_unset();
        session_destroy();

        self::_setAutoLogin(null);

        location('/');
    }
}
