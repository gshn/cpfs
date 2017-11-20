<?php
class User extends UserModel
{
    private static function _password($password)
    {
        $sql = 'SELECT PASSWORD(?) AS password';
        $rst = self::$pdo->query($sql, [$password]);
        $password = $rst->fetchColumn();

        return $password;
    }

    public function getVars($filters = null)
    {
        if ($filters === null) {
            $filters = [
                'id' => FILTER_VALIDATE_INT,
                'hp' => FILTER_SANITIZE_STRING,
                'name' => FILTER_SANITIZE_STRING,
                'password' => FILTER_UNSAFE_RAW,
                'secession' => FILTER_VALIDATE_BOOLEAN,
                'auto' => FILTER_VALIDATE_BOOLEAN
            ];
        }
        $vars = $this->_getVars($filters);

        return $vars;
    }

    private static function _setAutoLogin($user = null)
    {
        global $pdo;

        if ($user === null) {
            set_cookie('id', null);
            set_cookie('autoLogin', null);
        } else if (is_array($user)) {
            $hash = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$user['password']);
            set_cookie('id', $user['id']);
            set_cookie('autoLogin', $hash);
        }
    }

    public function getAutoLogin()
    {
        $id = (int)get_cookie('id');
        if (!empty($id)) {
            $user = self::getRow('id', $id);
            $hash_key = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$user['password']);
            $hash = get_cookie('autoLogin');
            if (!empty($hash) && $hash === $hash_key) {
                if ($user['secession'] === '회원') {
                    $_SESSION['id'] = $id;
                    return $user;
                }
            }
            self::_setAutoLogin(null);
        }

        return [];
    }

    public function login()
    {
        extract($this->getVars($filters = [
            'hp' => FILTER_SANITIZE_STRING,
            'password' => FILTER_UNSAFE_RAW,
            'auto' => FILTER_VALIDATE_BOOLEAN
        ]));

        $hp = get_hp_number($hp);
        $user = self::getRow('hp', $hp);

        if (empty($user['id']) || ($user['password'] !== self::_password($password))) {
            swal('실패!', '가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.', 'warning');
        }

        if ($user['secession'] === '탈퇴') {
            $date = date('y년 m월 d일', strtotime($user['timestamp']));
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

    public function logout()
    {
        session_unset();
        session_destroy();

        self::_setAutoLogin(null);

        location('/');
    }
}
