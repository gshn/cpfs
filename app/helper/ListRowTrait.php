<?php
interface ListRow
{
    public function list();
    public function listUpdate();
    public function row($id);
    public function rowUpdate();
}

trait ListRowTrait
{
    public function list()
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $total_count = $this->getTotalCount();
        $paging = $this->getPaging();
        $list = $this->getList();

        require VIEW.'/template/header.php';
        require VIEW.'/'.self::$namespace.'/'.self::$namespace.'-list.php';
        require VIEW.'/template/footer.php';
    }

    public function listUpdate()
    {
        $filters = [
            'req' => FILTER_SANITIZE_STRING,
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

        if ($req === 'list-delete') {
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

            swal('성공!', '일괄 삭제처리 했습니다.', 'success', '/'.self::$namespace.'?'.$qstr);
        } else if ($req === 'list-modify') {
            // foreach($ids as $id) {
            //     $arr = [
            //         'serial' => $serials[$id],
            //         'name' => $names[$id],
            //     ];
            //     $this->update($arr, 'WHERE id = ?', $id);
            // }

            swal('성공', '일괄 수정처리 했습니다.', 'success', '/'.self::$namespace.'?'.$qstr);
        }
    }

    public function row($id = null)
    {
        $qstr = self::getQueryString();

        $cols = $this->getColumn('name');
        $row = $this->getRow('id', $id);

        require VIEW.'/template/header.php';
        require VIEW.'/'.self::$namespace.'/'.self::$namespace.'-row.php';
        require VIEW.'/template/footer.php';
    }

    public function rowUpdate()
    {
        $qstr = self::getQueryString();
        $vars = $this->_getVars();
        extract($vars);

        if (!empty($id)) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = self::$pdo::lastInsertId();
        }

        swal('성공!', '정보를 입력 했습니다.', 'success', '/'.self::$namespace.'/row/'.$id.'?'.$qstr);
    }
}
