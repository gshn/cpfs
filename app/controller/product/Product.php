<?php
class Product extends ProductModel implements ListRow
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list($prod = null)
    {
        extract(self::_getQueryVars());
        $qstr = self::getQueryString();

        if ($prod !== null) {
            $this->where .= " AND prod_{$prod} = 'y' ";
        }

        $total_count = $this->getTotalCount();
        $paging = $this->getPaging();
        $list = $this->getList();

        require VIEW.'/template/header.php';
        require VIEW.'/product/product-list.php';
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

        $cols = $this->getColumn('name');
        $row = $this->getRow('pro.id', $id);

        require VIEW.'/template/header.php';
        require VIEW.'/product/product-row.php';
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
        $vars = $this->_getVars();
        extract($vars);

        $this->update($vars, 'WHERE id = ?', $id);

        swal('성공!', '상품 정보를 수정 했습니다.', 'success', '/product/row/'.$id.'?'.$qstr);
    }

    public function getProd($row)
    {
        foreach($row as $key => $value) {
            if ($value === 'y') {
                $row[$key] = true;
            } else if ($value === 'n') {
                $row[$key] = false;
            }
        }
        extract($row);
        $prod = null;
        if($prod_used) {
            $prod .= '(중고물품)';
        }
        if($prod_donate) {
            $prod .= '(기부)';
        }
        if($prod_collect) {
            $prod .= '(수거)';
        }
        if($prod_demolition) {
            $prod .= '(철거)';
        }
        if($prod_res_sell) {
            $prod .= '(자원판매)';
        }
        if($prod_res_share_biz) {
            $prod .= '(공유경제)';
        }
        if($prod_hr_get_job) {
            $prod .= '(구직)';
        }
        if($prod_hr_get_human) {
            $prod .= '(구인)';
        }
        if($prod_rental_sell) {
            $prod .= '(장비대여)';
        }
        if($prod_rental_buy) {
            $prod .= '(장비구함)';
        }
        if($prod_arrange_normal) {
            $prod .= '(일반정리)';
        }
        if($prod_arrange_relic) {
            $prod .= '(유품정리)';
        }

        return $prod;
    }
}
