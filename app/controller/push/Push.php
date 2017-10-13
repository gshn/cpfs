<?php
class Push extends PushModel implements ListRow
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
        require VIEW.'/push/push-list.php';
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

        swal('성공!', '일괄 삭제처리 했습니다.', 'success', '/push?'.$qstr);
    }

    public function row($id = null)
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $cols = $this->getColumn('name');
        $row = $this->getRow('id', $id);

        require VIEW.'/template/header.php';
        require VIEW.'/push/push-row.php';
        require VIEW.'/template/footer.php';
    }

    public function rowUpdate()
    {
        $qstr = self::getQueryString();
        $vars = $this->_getVars();
        extract($vars);

        if ($id !== null) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = self::$pdo::lastInsertId();
        }

        swal('성공!', '정보를 입력 했습니다.', 'success', '/push/row/'.$id.'?'.$qstr);
    }

    public function send($id)
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        $row = $this->getRow('id', $id);

        $url = PUSH_SERVER_ADDRESS;

        $paramter = array(
            'req' => 'MSG_Total_Send',
            'data' => array(
                'service_name' => 'gomool',
                'title' => $row['title'],
                'contents' => $row['contents'],
                'bigo' => '관리자 푸시전송'
            )
        );

        $paramter = "json=" . json_encode($paramter);

        // Open connection
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paramter);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);

        swal('성공!', '푸시를 전송했습니다.', 'success', '/push/?'.$qstr);
    }
}
