<?php
class User extends UserModel implements ListRow
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list()
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();
        $total_count = $this->getTotalCount();
        $paging = $this->getPaging();
        $list = $this->getList();

        require VIEW.'/template/header.php';
        require VIEW.'/user/user-list.php';
        require VIEW.'/template/footer.php';
    }

    public function listUpdate()
    {
        $filters = [
            'ids' => [
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_FORCE_ARRAY,
                'options' => [
                    'min_range' => 1
                ]
            ]
        ];
        extract($this->_getVars($filters));

        $qstr = self::getQueryString();

        $sql_where = " WHERE ( ";
        $cnt = count($ids);
        $i = 0;
        foreach($ids as $id) {
            $sql_where .= " id = '{$id}' ";

            $i += 1;
            if ($cnt !== $i) {
                $sql_where .= " OR ";
            }
        }
        $sql_where .= " ) ";

        $this->update([
            'delete_date' => YMDHIS,
            'hidden' => 'y'
        ], $sql_where);

        swal('성공!', '사용자를 일괄 탈퇴처리 했습니다.', 'success', '/user?'.$qstr);
    }

    public function row($id)
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $row = $this->getRow('id', $id);

        require VIEW.'/template/header.php';
        require VIEW.'/user/user-row.php';
        require VIEW.'/template/footer.php';
    }

    public function password($password)
    {
        $sql = "SELECT PASSWORD({$password})";
        return self::$pdo::query($sql)->fetchColumn();
    }

    public function rowUpdate()
    {
        $qstr = self::getQueryString();
        $filters = [
            'id' => FILTER_VALIDATE_INT,
            'account_type' => FILTER_SANITIZE_STRING,
            'login_id' => FILTER_SANITIZE_STRING,
            'email' => FILTER_VALIDATE_EMAIL,
            'nickname' => FILTER_SANITIZE_STRING,
            'hp' => FILTER_SANITIZE_STRING,
            'postzip' => FILTER_SANITIZE_STRING,
            'addr_01' => FILTER_SANITIZE_STRING,
            'addr_02' => FILTER_SANITIZE_STRING
        ];
        $vars = $this->_getVars($filters);
        extract($vars);

        $login_pw = isset($_REQUEST['login_pw']) ? $_REQUEST['login_pw'] : null;

        if (empty($id)) {
            swal('실패!', '수정할 사용자가 선택되지 않았습니다.', 'warning');
        }

        if (empty($account_type)) {
            swal('실패!', '계정타입을 입력해주세요.', 'warning');
        }

        if (empty($login_id)) {
            swal('실패!', '아이디를 입력해주세요.', 'warning');
        }

        if (empty($email)) {
            swal('실패!', '이메일을 입력해주세요.', 'warning');
        }

        if (empty($hp)) {
            swal('실패!', '휴대전화를 입력해주세요.', 'warning');
        }

        if (!empty($login_pw)) {
            $vars['login_pw'] = $this->password($login_pw);
        }

        $this->update($vars, 'WHERE id = ?', $id);

        swal('성공!', '사용자 정보를 수정 했습니다.', 'success', '/user/row/'.$id.'?'.$qstr);
    }
}
