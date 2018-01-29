<?php
namespace controller;

use helper\Library;
use helper\Route;
use model\UserModel;

class User extends UserModel
{
    /**
     * 비밀번호 해시 함수 mysql PASSWORD 함수 이용
     * @param string password
     * @return string password 41bytes
     */
    private static function _password($password)
    {
        $sql = 'SELECT PASSWORD(?) AS password';
        $rst = parent::$pdo->query($sql, [$password]);
        $password = $rst->fetchColumn();

        return $password;
    }

    /**
     * 자동로그인 해시 함수
     * @param string password
     * @return string hash
     */
    private static function _hash($password)
    {
        return md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$password);
    }

    /**
     * 자동로그인 setter
     * null일 경우 자동로그인 쿠키를 삭제함
     * @param array user | null
     * @return void
     */
    private static function _setAutoLogin($user = null)
    {
        global $pdo;

        if ($user === null) {
            Library::setCookie('id', null);
            Library::setCookie('autoLogin', null);
        } else if (is_array($user)) {
            $hash = self::_hash($user['password']);
            Library::setCookie('id', $user['id']);
            Library::setCookie('autoLogin', $hash);
        }
    }

    /**
     * 자동로그인 getter
     * @return array user | null
     */
    public function getAutoLogin()
    {
        $id = (int)Library::getCookie('id');
        if (!empty($id)) {
            $user = $this->getRow('id', $id);
            $hash_key = self::_hash($user['password']);
            $hash = Library::getCookie('autoLogin');
            if (!empty($hash) && $hash === $hash_key) {
                $_SESSION['id'] = $id;
                return $user;
            }
            self::_setAutoLogin(null);
        }

        return [];
    }

    /**
     * 로그인
     * @param string email(필수)
     * @param string password(필수)
     * @param bool auto
     * @param string url
     */
    public function login()
    {
        extract(parent::_getVars($filters = [
            'email' => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_UNSAFE_RAW,
            'auto' => FILTER_VALIDATE_BOOLEAN,
            'url' => FILTER_VALIDATE_URL
        ]));

        $user = $this->getRow('email', $email);

        if (empty($user['id']) || ($user['password'] !== self::_password($password))) {
            swal('실패!', '가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.', 'warning');
        }

        $_SESSION['id'] = $user['id'];

        if (empty($auto)) {
            self::_setAutoLogin(null);
        } else {
            self::_setAutoLogin($user);
        }

        if (empty($url)) {
            Route::location('/');
        } else {
            Route::location($url);
        }
    }

    public static function logout()
    {
        session_unset();
        session_destroy();

        self::_setAutoLogin(null);

        Route::location('/');
    }

    public static function formLogin()
    {
        extract(Library::getVars($filters = [
            'url' => FILTER_VALIDATE_URL
        ]));

        Route::template('/user/login', [
            'url' => $url ?? null
        ], 'no-header');
    }

    public function rows()
    {
        define('USER', TRUE);

        extract(self::getQueryVars());
        $qstr = self::getQueryString();

        $table_text = $this->table_text ?? ucfirst(self::$namespace);
        $count = $this->getTotalCount();
        $paging = $this->getPaging();
        $inputs = $this->getQueryStringInput();
        $list = $this->getList();

        $cols = [
            'id' => $this->getOrderBy('#', 'id'),
            'email' => $this->getOrderBy('이메일', 'email'),
            'timestamp' => $this->getOrderBy('로그인일시', 'timestamp'),
            'datetime' => $this->getOrderBy('등록일시', 'datetime')
        ];

        Route::template('/template/'.SKIN.'/list', [
            'table_text' => $table_text,
            'stx' => $stx,
            'qstr' => $qstr,
            'count' => $count,
            'paging' => $paging,
            'inputs' => $inputs,
            'list' => $list,
            'cols' => $cols
        ], 'header');
    }

    public function row($id = null)
    {
        define('USER', TRUE);
        define('CKEDITOR', TRUE);

        $qstr = self::getQueryString();

        $table_text = $this->table_text ?? ucfirst(self::$namespace);
        $row = $this->getRow('id', $id);
        $inputs = self::getQueryStringInput();

        $cols = [
            '이메일' => [
                'type' => 'text',
                'name' => 'email',
                'value' => $row['email'],
                'attribute' => ' required maxlength="100"',
                'placeholder' => '이메일 입력해 주세요.'
            ],
            '새로운 비밀번호' => [
                'type' => 'text',
                'name' => 'password',
                'value' => '',
                'attribute' => '',
                'placeholder' => '새로운 비밀번호를 입력해 주세요.'
            ]
        ];

        if ((bool) $row['id']) {
            $cols['로그인일시'] = [
                'type' => 'datetime-local',
                'name' => '',
                'value' => date('Y-m-d\TH:i', strtotime($row['timestamp'])),
                'attribute' => ' readonly',
                'placeholder' => ''
            ];

            $cols['등록일시'] = [
                'type' => 'datetime-local',
                'name' => '',
                'value' => date('Y-m-d\TH:i', strtotime($row['datetime'])),
                'attribute' => ' readonly',
                'placeholder' => ''
            ];
        }

        Route::template('/template/'.SKIN.'/row', [
            'table_text' => $table_text,
            'qstr' => $qstr,
            'row' => $row,
            'inputs' => $inputs,
            'cols' => $cols
        ], 'header');
    }

    public function rowUpdate()
    {
        $qstr = parent::getQueryString();
        extract($vars = $this->_getVars());

        if (!empty($password)) {
            $vars['password'] = self::_password($password);
        }

        if (!empty($id)) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = parent::$pdo->lastInsertId();
        }

        Route::location('/'.self::$namespace.'/row/'.$id.'?'.$qstr);
    }
}
