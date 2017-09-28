<?php
/**
 * model 관리를 편리하게 도와주는 클래스
 * 공통적으로 자주 쓰이는 함수를 모음
 * 기본적으로 정해진 sql문은 미리 설정해두고 필요하면 오버라이딩해서 사용
 * @author hheo (hheo@cozmoworks.com)
 * @see /app/model/Database.model.php
 * @see /app/common.php
 */
abstract class ModelHelper
{
    // 한 페이지 게시물 수
    const ROWS = 20;
    // 페이징 표시 개수
    const PAGES = 5;

    // PDO interface
    static $pdo;
    // 사용할 테이블명
    static $table;

    protected $common;
    protected $select;
    protected $where;
    protected $order;
    protected $limit;

    public function __construct($table = null, $connect = null)
    {
        global $pdo;

        self::$table = $table === null ? strtolower(get_class($this)) : $table;
        self::$pdo = $connect === null ? $pdo : $connect;

        $this->common = 'FROM '.self::$table;
        $this->select = '*';
        $this->where = ' WHERE (1) ';
    }

    private static function _getQueryVars()
    {
        $vars = [
            'sfl' => FILTER_SANITIZE_STRING,
            'stx' => FILTER_SANITIZE_STRING,
            'sst' => FILTER_SANITIZE_STRING,
            'sod' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => [
                    'regexp' => '/^(asc|desc|ASC|DESC)$/'
                ]
            ],
            'rows' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'default' => self::ROWS,
                    'min_range' => 1
                ]
            ],
            'page' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'default' => 1,
                    'min_range' => 1
                ]
            ]
        ];

        return get_vars($vars);
    }

    private function _getTotalPage()
    {
        extract(self::_getQueryVars());

        $total = ceil($this->getTotalCount() / $rows);

        return $total;
    }

    protected static function _getVars($filters)
    {
        $args = $vars = [];

        foreach($filters as $key => $value) {
            $args[$key] = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
        }

        $vars = filter_var_array($args, $filters);

        return $vars;
    }

    public function getQueryString()
    {
        $qstr = null;
        foreach(self::_getQueryVars() as $key => $value) {
            if (!empty($value)) {
                $qstr .= '&'.$key.'='.urlencode($value);
            }
        }

        return $qstr;
    }

    public function getTotalCount($sql_count = 'COUNT(*)')
    {
        $sql = "SELECT {$sql_count}
                {$this->common}
                {$this->where}";
        $count = self::$pdo::query($sql)->fetchColumn();

        return $count;
    }

    public function getRow($key, $value, $select = '*')
    {
        $sql = "SELECT {$select}
                {$this->common}
                WHERE {$key} = ?";
        $row = self::$pdo::query($sql, [$value])->fetch();

        return $row;
    }

    public function getList($limit = null)
    {
        extract(self::_getQueryVars());

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        }

        if ($limit === null) {
            $offset = ($page - 1) * $rows;
            $this->limit = "LIMIT {$offset}, {$rows}";
        } else {
            $this->limit = $limit;
        }

        $sql = "SELECT {$this->select}
                {$this->common}
                {$this->where}
                {$this->order}
                {$this->limit}";
        $list = self::$pdo::query($sql)->fetchAll();

        return $list;
    }

    public function getPaging()
    {
        extract(self::_getQueryVars());

        $total = $this->_getTotalPage();
        $qstr = $this->getQueryString();

        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];

        $qstr = preg_replace('#&page=[0-9]*#', '', $qstr);
        $qstr = preg_replace('#&amp;page=[0-9]*#', '', $qstr);
        $url = $uri.'?'.$qstr.'&amp;page=';
        $str = '<li><a href="'.$url.'1">«</a></li>'.PHP_EOL;

        $start = $page - floor(self::PAGES / 2);
        if ($start < 1) {
            $start = 1;
        }

        $end = $start + self::PAGES - 1;
        if ($end >= $total) {
            $end = $total;
        }

        if(($end - $start + 1) < self::PAGES) {
            $start = $end - self::PAGES + 1;
            if ($start < 1) {
                $start = 1;
            }
        }

        if ($start > 1) {
            $str .= '<li><a href="'.$url.($start - 1).'"><</a></li>'.PHP_EOL;
        } else {
            $str .= ''.PHP_EOL;
        }

        for ($i = $start; $i <= $end; $i += 1) {
            if ($page != $i)
                $str .= '<li><a href="'.$url.$i.'">'.$i.'</a></li>'.PHP_EOL;
            else
                $str .= '<li class="active"><a href="#">'.$i.'</a></li>'.PHP_EOL;
        }

        if ($total > $end) {
            $str .= '<li><a href="'.$url.($end + 1).'">></a></li>'.PHP_EOL;
        }

        $str .= '<li><a href="'.$url.$total.'">»</a></li>'.PHP_EOL;
        $str = "<ul class=\"pagination pagination-sm\">{$str}</ul>";

        return $str;
    }

    public function getOrderBy($text, $col, $flag = 'ASC')
    {
        if (empty($col) || !property_exists(get_class($this), $col)) {
            $anchor = "<a href=\"{$_SERVER['REQUEST_URI']}\">1{$text}</a>";
            return $anchor;
        }

        extract(self::_getQueryVars());
        $qstr = $this->getQueryString();
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        $qstr = preg_replace('#&sst=.*&sod=(asc|desc|ASC|DESC)#', '', $qstr);
        $qstr = preg_replace('#&amp;sst=.*&amp;sod=(asc|desc|ASC|DESC)#', '', $qstr);

        $q1 = 'sst='.$col;
        if ($flag === 'asc' || $flag === 'ASC') {
            $q2 = 'sod=ASC';
            if ($sst === $col) {
                if ($sod === 'asc' || $sod === 'ASC') {
                    $q2 = 'sod=DESC';
                }
            }
        } else {
            $q2 = 'sod=DESC';
            if ($sst == $col) {
                if ($sod == 'desc' || $sod == 'DESC') {
                    $q2 = 'sod=ASC';
                }
            }
        }

        $arr_qstr = [];
        $arr_qstr[] = 'sfl='.$sfl;
        $arr_qstr[] = 'stx='.$stx;
        $arr_qstr[] = $q1;
        $arr_qstr[] = $q2;
        $arr_qstr[] = 'rows='.$rows;
        $arr_qstr[] = 'page='.$page;
        $qstr = implode('&amp;', $arr_qstr);
        $anchor = "<a href=\"{$uri}?{$qstr}\">{$text}</a>";

        return $anchor;
    }

    public function insert($arr)
    {
        $sql = 'INSERT INTO ' . self::$table . ' SET ';
        $values = [];
        foreach($arr as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                $sql .= " {$key} = ? ";
                $values[] = $value;
                end($arr);
                if ($key !== key($arr)) {
                    $sql .= ', ';
                }
            }
        }
        $rst = self::$pdo::query($sql, $values);

        return $rst;
    }

    public function update($arr, $col, $word)
    {
        $sql = 'UPDATE ' . self::$table . ' SET ';
        $values = [];
        foreach($arr as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                $sql .= " {$key} = ? ";
                $values[] = $value;
                end($arr);
                if ($key !== key($arr)) {
                    $sql .= ', ';
                }
            }
        }
        $sql .= " WHERE {$col} = ? ";
        $values[] = $word;
        echo $sql;
        vardump($values);
        $rst = self::$pdo::query($sql, $values);

        return $rst;
    }

    public function delete($key, $value)
    {
        $sql = "DELETE
                {$this->common}
                WHERE {$key} = ?";
        $row = self::$pdo::query($sql, [$value]);

        return $rst;
    }
}
