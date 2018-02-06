<?php
/**
 * User.php
 * 
 * PHP Version 7
 * 
 * @category Controller
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
namespace controller;

use helper\Library;
use helper\Route;
use model\UserModel;

/**
 * User Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
class User extends UserModel
{
    /**
     * 비밀번호 해시 함수 mysql PASSWORD 함수 이용
     * 
     * @param string $password 비밀번호
     * 
     * @return string 41bytes
     */
    private static function _password(string $password)
    {
        $sql = 'SELECT PASSWORD(?) AS password';
        $rst = parent::$pdo->query($sql, [$password]);
        $password = $rst->fetchColumn();

        return $password;
    }

    /**
     * 자동로그인 해시 함수
     * 
     * @param string $password 비밀번호
     * 
     * @return string
     */
    private static function _hash(string $password)
    {
        return sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$password);
    }

    /**
     * 자동로그인 setter
     * null일 경우 자동로그인 쿠키를 삭제함
     * 
     * @param array|null $user 사용자
     * 
     * @return bool
     */
    private static function _setAutoLogin($user = null)
    {
        if ($user === null) {
            Library::setCookie('id', null);
            Library::setCookie('autoLogin', null);
        } else if (is_array($user)) {
            $hash = self::_hash($user['password']);
            Library::setCookie('id', $user['id']);
            Library::setCookie('autoLogin', $hash);
        }

        return true;
    }

    /**
     * 자동로그인 getter
     * 
     * @return array|null
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
     * Login
     * 
     * @return void
     */
    public function login()
    {
        /**
         * POST /login
         * 
         * @param string $email    이메일(필수)
         * @param string $password 비밀번호(필수)
         * @param bool   $auto     자동로그인
         * @param string $url      로그인 후 이동할 URL
         */
        $validateVars = [
            'email' => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_UNSAFE_RAW,
            'auto' => FILTER_VALIDATE_BOOLEAN,
            'url' => FILTER_VALIDATE_URL
        ];

        extract(parent::validateVars($validateVars));

        $user = $this->getRow('email', $email);

        if (empty($user['id'])
            || ($user['password'] !== self::_password($password))
        ) {
            $swal = '가입된 회원아이디가 아니거나 비밀번호가 틀립니다.';
            swal('실패!', $swal, 'warning');
        }

        $_SESSION['id'] = $user['id'];

        if (empty($auto)) {
            self::_setAutoLogin(null);
        } else {
            self::_setAutoLogin($user);
        }

        if (empty($url)) {
            return Route::location('/');
        } else {
            return Route::location($url);
        }
    }

    /**
     * 로그아웃 모든 세션 파괴 후 자동로그인 쿠키 삭제
     * 
     * @return void
     */
    public static function logout()
    {
        session_unset();
        session_destroy();

        self::_setAutoLogin(null);

        return Route::location('/');
    }

    /**
     * 폼 로그인
     * 
     * @return void
     */
    public static function formLogin()
    {
        /**
         * GET /login
         * 
         * @param string $url 로그인 후 해당 url로 이동
         */
        $vars = [
            'url' => FILTER_VALIDATE_URL
        ];

        extract(Library::vars($vars));

        $template = [
            'url' => $url ?? null
        ];

        return Route::template('/user/login', $template, 'no-header');
    }

    /**
     * 회원 목록
     * helper\Model
     * 
     * @return void
     */
    public function rows()
    {
        define('USER', true);

        $template = [
            'heading' => parent::heading(),
            'count' => $this->totalCount(),
            'paging' => $this->paging(),
            'inputs' => $this->queryStringsInput(),
            'list' => $this->getRows(),
            'cols' => [
                'id' => $this->orderBy('#', 'id'),
                'email' => $this->orderBy('이메일', 'email'),
                'timestamp' => $this->orderBy('로그인일시', 'timestamp'),
                'datetime' => $this->orderBy('등록일시', 'datetime')
            ]
        ];

        return Route::template('/template/'.SKIN.'/list', $template, 'header');
    }

    /**
     * 회원 등록 / 수정
     * helper\Model
     * 
     * @param int $id #
     * 
     * @return void
     */
    public function row($id = null)
    {
        $row = $this->getRow('id', $id);

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

        $template = [
            'heading' => parent::heading(),
            'inputs' => parent::queryStringsInput(),
            'row' => $row,
            'cols' => $cols
        ];

        return Route::template('/template/'.SKIN.'/row', $template, 'header');
    }

    /**
     * 회원 정보 업데이트
     * helper\Model
     * 
     * @return void
     */
    public function rowUpdate()
    {
        $qstr = parent::queryString();
        extract($vars = parent::validateVars());

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
