<?php
trait BoardMethod
{
    public function list()
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $total_count = $this->getTotalCount();
        $paging = $this->getPaging();
        $list = $this->getList();

        require VIEW.'/template/header.php';
        require VIEW.'/board/board-list.php';
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
            ],
            'tblname' => FILTER_SANITIZE_STRING
        ];
        extract($this->_getVars($filters));

        $qstr = self::getQueryString();

        $where = " WHERE ( ";
        $cnt = count($ids);
        $i = 0;
        foreach($ids as $id) {
            $where .= " id = '{$id}' ";

            $i += 1;
            if ($cnt !== $i) {
                $where .= " OR ";
            }
        }
        $where .= " ) ";

        $this->delete($where);

        swal('성공!', '게시물을 일괄 삭제처리 했습니다.', 'success', '/board/'.$tblname.'/?'.$qstr);
    }

    public function row($id = null)
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $cols = $this->getColumn('name');
        $row = $this->getRow('id', $id);

        require VIEW.'/template/header.php';
        require VIEW.'/board/board-row.php';
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

        $vars['create_date'] = YMDHIS;
        if ($id !== null) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = self::$pdo::lastInsertId();
        }

        swal('성공!', '정보를 입력 했습니다.', 'success', '/board/'.$tblname.'/row/'.$id.'?'.$qstr);
    }
}
