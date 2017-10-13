<?php
class Qna extends QnaModel implements ListRow
{
    public function __construct($table = 'tblqna')
    {
        parent::__construct($table);
    }

    public function list()
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $total_count = $this->getTotalCount();
        $paging = $this->getPaging();
        $origin = $this->getList();

        $list = [];
        foreach($origin as $row) {
            $row['reply'] = $this->getReplyList($row['id']);
            $row['reply_count'] = count($row['reply']);
            $list[] = $row;
        }

        require VIEW.'/template/header.php';
        require VIEW.'/board/qna-list.php';
        require VIEW.'/template/footer.php';
    }

    public function row($id = null)
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $cols = $this->getColumn('name');
        $row = $this->getRow('qna.id', $id);

        require VIEW.'/template/header.php';
        require VIEW.'/board/qna-row.php';
        require VIEW.'/template/footer.php';
    }

    public function rowUpdate()
    {
        $filters = [
            'tblname' => FILTER_SANITIZE_STRING
        ];
        extract($this->_getVars($filters));

        $qstr = self::getQueryString();
        $vars = $this->_getVars();
        extract($vars);

        $vars['res_date'] = YMDHIS;

        if ($id !== null) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = self::$pdo::lastInsertId();
        }

        swal('성공!', '정보를 입력 했습니다.', 'success', '/board/'.$tblname.'/row/'.$id.'?'.$qstr);
    }

    public function push($id)
    {
    }
}
