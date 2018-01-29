<?php
/**
 * model 관리를 편리하게 도와주는 클래스
 * 공통적으로 자주 쓰이는 함수를 모음
 * 기본적으로 정해진 sql문은 미리 설정해두고 필요하면 오버라이딩해서 사용
 * @see /app/model/Database.model.php
 * @see /app/core.php
**/
namespace helper;

use PDO;
use helper\Library;
use helper\Route;

abstract class Model
{
    // 한 페이지 게시물 수
    const ROWS = 20;
    // 페이징 표시 개수
    const PAGES = 5;

    // PDO interface
    static $pdo;
    // 사용할 테이블명
    static $table;
    // 사용할 네임스페이스
    static $namespace;

    public $common;
    public $select;
    public $where;
    public $order;
    public $group;
    public $limit;
    public $count;

    public $table_text;

    public function __construct($table = null, $connect = null)
    {
        $pdo = new Database();
        $class = explode('\\', strtolower(get_class($this)));

        self::$table = $table === null ?  end($class) : $table;
        self::$pdo = $connect === null ? $pdo : $connect;
        self::$namespace = end($class);

        $this->common = 'FROM '.self::$table;
        $this->select = '*';
        $this->where = ' WHERE (1) ';
        $this->count = -1;
    }

    public static function getQueryVars()
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

        return Library::getVars($vars);
    }

    protected function _getTotalPage()
    {
        extract(self::getQueryVars());

        if ($this->count === -1) {
            $this->count = $this->getTotalCount();
        }
        $total = ceil($this->count / $rows);

        return $total;
    }

    protected static function _emptyCheck($filters, $vars = null)
    {
        if ($vars === null) {
            $vars = $_REQUEST;
        }

        foreach($filters as $key => $value) {
            if(empty($vars[$key])) {
                return $value;
            }
        }
        
        return TRUE;
    }

    protected static function _getVars($filters = null)
    {
        $args = $vars = [];

        if ($filters === null) {
            $cols = self::getColumn('name');
            $filters = [];
            foreach($cols as $col) {
                $filters[$col['name']] = FILTER_SANITIZE_STRING;
            }
        }

        foreach($filters as $key => $value) {
            if(isset($_REQUEST[$key])) {
                $args[$key] = $_REQUEST[$key];
            } else {
                unset($filters[$key]);
            }
        }

        $vars = filter_var_array($args, $filters);

        return $vars;
    }

    public static function getQueryString()
    {
        $qstr = null;
        foreach(self::getQueryVars() as $key => $value) {
            if (!empty($value)) {
                $qstr .= '&'.$key.'='.rawurlencode($value);
            }
        }

        return $qstr;
    }

    public static function getQueryStringInput()
    {
        $qstrInput = null;
        foreach(self::getQueryVars() as $key => $value) {
            if (!empty($value)) {
                $qstrInput .= '<input type="hidden" name="'.$key.'" value="'.$value.'">'.PHP_EOL;
            }
        }

        return $qstrInput;
    }

    public function getTotalCount($sql_count = 'COUNT(*)')
    {
        $sql = "SELECT {$sql_count}
                {$this->common}
                {$this->where}";
        $count = (int)self::$pdo::query($sql)->fetchColumn();

        $this->count = $count;
        return $count;
    }

    public function getRow($key, $value, $select = '*')
    {
        if ($select !== '*') {
            $this->select = $select;
        }

        $sql = "SELECT {$this->select}
                {$this->common}
                WHERE {$key} = ?";
        $row = self::$pdo::query($sql, [$value])->fetch();

        return $row;
    }

    public function getList($order = null, $limit = null, $fetch = PDO::FETCH_ASSOC)
    {
        extract(self::getQueryVars());

        if ($order !== null) {
            $this->order = $order;
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
                {$this->group}
                {$this->order}
                {$this->limit}";
        $list = self::$pdo::query($sql)->fetchAll($fetch);

        return $list;
    }

    public function getPaging()
    {
        extract(self::getQueryVars());

        $total = $this->_getTotalPage();
        $qstr = self::getQueryString();

        $qstr = preg_replace('#&page=[0-9]*#', '', $qstr);
        $qstr = preg_replace('#&amp;page=[0-9]*#', '', $qstr);
        $url = URI.'?'.$qstr.'&amp;page=';
        $str = '<li class="page-item"><a class="page-link" href="'.$url.'1"><span aria-hidden="true">처음</span><span class="sr-only">처음</span></a></li>'.PHP_EOL;

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
            $str .= '<li class="page-item"><a class="page-link" href="'.$url.($start - 1).'">이전</a></li>'.PHP_EOL;
        } else {
            $str .= ''.PHP_EOL;
        }

        for ($i = $start; $i <= $end; $i += 1) {
            if ($page != $i)
                $str .= '<li class="page-item"><a class="page-link" href="'.$url.$i.'">'.$i.'</a></li>'.PHP_EOL;
            else
                $str .= '<li class="page-item active"><a class="page-link" href="#">'.$i.'</a></li>'.PHP_EOL;
        }

        if ($total > $end) {
            $str .= '<li class="page-item"><a class="page-link" href="'.$url.($end + 1).'">다음</a></li>'.PHP_EOL;
        }

        $str .= '<li class="page-item"><a class="page-link" href="'.$url.$total.'"><span aria-hidden="true">마지막</span>
        <span class="sr-only">마지막</span></a></li>'.PHP_EOL;
        $str = "<ul class=\"pagination\">{$str}</ul>";

        return $str;
    }

    public function getOrderBy($text, $col, $flag = 'ASC', $class = null)
    {
        if (strpos($col, '.') > 0) {
            $cols = explode('.', $col);
            $property = end($cols);
        } else {
            $property = $col;
        }

        extract(self::getQueryVars());
        $qstr = self::getQueryString();
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
        $anchor = '<a ';
        if ($class !== null) {
            $anchor .= "class=\"$class\" ";
        }
        $anchor .= "href=\"".URI."?{$qstr}\">{$text}</a>";

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

    public function update($arr, $where, $index = null)
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
        $sql .= " {$where} ";
        if ($index !== null) {
            $values[] = $index;
        }
        $rst = self::$pdo::query($sql, $values);

        return $rst;
    }

    public function delete($where, $index = null)
    {
        $values = [];
        $sql = 'DELETE FROM ' . self::$table;
        $sql .= " {$where} ";
        if ($index !== null) {
            if (is_array($index)) {
                $values = $index;
            } else {
                $values[] = $index;
            }
        }

        $rst = self::$pdo::query($sql, $values);

        return $rst;
    }

    public static function getColumn($needle = 'COLUMN_COMMENT')
    {
        $sql = "SELECT COLUMN_COMMENT, COLUMN_NAME
                FROM information_schema.COLUMNS
                WHERE TABLE_NAME = '".self::$table."'
                AND TABLE_SCHEMA = '".self::$pdo::$name."'
                ORDER BY ORDINAL_POSITION ASC";
        $list = self::$pdo::query($sql)->fetchAll();

        $cols = [];
        $i = 0;
        foreach($list as $row) {
            if ($needle === 'COLUMN_COMMENT') {
                $cols[$i] = empty($row['COLUMN_COMMENT']) ? $row['COLUMN_NAME'] : $row['COLUMN_COMMENT'];
            } else {
                $cols[$i]['comment'] = empty($row['COLUMN_COMMENT']) ? $row['COLUMN_NAME'] : $row['COLUMN_COMMENT'];
                $cols[$i]['name'] = $row['COLUMN_NAME'];
            }

            $i += 1;
        }

        return $cols;
    }

    public function getCols()
    {
        $list = $this->getColumn(false);
        $cols = [];
        foreach ($list as $row) {
            $cols[$row['name']] = $this->getOrderBy($row['comment'], $row['name']);
        }

        return $cols;
    }

    public function rows()
    {
        extract(self::getQueryVars());
        $qstr = self::getQueryString();

        $table_text = $this->table_text ?? ucfirst(self::$namespace);
        $count = $this->getTotalCount();
        $paging = $this->getPaging();
        $list = $this->getList();
        $cols = $this->getCols();
        $inputs = $this->getQueryStringInput();

        if (is_file(VIEW.'/'.self::$namespace.'/'.self::$namespace.'-list.php')) {
            $template = '/'.self::$namespace.'/'.self::$namespace.'-list';
        } else {
            $template = '/template/'.SKIN.'/list';
        }

        Route::template($template, [
            'table_text' => $table_text,
            'stx' => $stx,
            'qstr' => $qstr,
            'count' => $count,
            'paging' => $paging,
            'list' => $list,
            'cols' => $cols,
            'inputs' => $inputs
        ], 'header');
    }

    public function rowsUpdate()
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

            Route::location('/'.self::$namespace.'?'.$qstr);
        } else if ($req === 'list-modify') {
            // foreach($ids as $id) {
            //     $arr = [
            //         'serial' => $serials[$id],
            //         'name' => $names[$id],
            //     ];
            //     $this->update($arr, 'WHERE id = ?', $id);
            // }
        }

        Route::location('/'.self::$namespace.'?'.$qstr);
    }

    public function row($id = null)
    {
        $qstr = self::getQueryString();

        $table_text = $this->table_text ?? ucfirst(self::$namespace);
        $row = $this->getRow('id', $id);
        $inputs = self::getQueryStringInput();
        $cols = self::getColumn('name');

        if (is_file(VIEW.'/'.self::$namespace.'/'.self::$namespace.'-row.php')) {
            $template = '/'.self::$namespace.'/'.self::$namespace.'-row';
        } else {
            $template = '/template/'.SKIN.'/row';
        }

        Route::template($template, [
            'table_text' => $table_text,
            'qstr' => $qstr,
            'row' => $row,
            'inputs' => $inputs,
            'cols' => $cols
        ], 'header');
    }

    public function rowUpdate()
    {
        $qstr = self::getQueryString();
        extract($vars = $this->_getVars());

        if (!empty($id)) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = self::$pdo->lastInsertId();
        }

        Route::location( '/'.self::$namespace.'/row/'.$id.'?'.$qstr);
    }
}
