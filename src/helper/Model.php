<?php
/**
 * Model.php
 * model 관리를 편리하게 도와주는 클래스
 * 공통적으로 자주 쓰이는 함수를 모음
 * 기본적으로 정해진 sql문은 미리 설정해두고 필요하면 오버라이딩해서 사용
 *
 * PHP Version 7
 * 
 * @category Helper
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
namespace helper;

use PDO;
use helper\Library;
use helper\Route;

/**
 * Model Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
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
    public $heading;

    /**
     * Function __construct
     * 
     * @param string $table   테이블명
     * @param object $connect 기본 PDO를 사용하지 않을 경우
     * 
     * @return null
     */
    public function __construct($table = null, $connect = null)
    {
        $pdo = new Database();
        $class = explode('\\', strtolower(get_class($this)));

        self::$table = $table ?? end($class);
        self::$pdo = $connect ?? $pdo;
        self::$namespace = end($class);

        $this->common = 'FROM '.self::$table;
        $this->select = '*';
        $this->where = ' WHERE (1) ';
        $this->count = -1;

        return null;
    }

    /**
     * 게시물 검색, 정렬시 사용하는 변수 모음
     * $sfl 검색할 컬럼명
     * $stx 검색어
     * $sst 정렬햘 컬럼명
     * $sod 정렬 방식
     * 
     * @return array
     */
    public static function queryStrings()
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

        return Library::vars($vars);
    }

    /**
     * 쿼리스트링 생성
     * 
     * @return string
     */
    public static function queryString()
    {
        $qstr = null;
        foreach (self::queryStrings() as $key => $value) {
            if (!empty($value)) {
                $qstr .= '&'.$key.'='.rawurlencode($value);
            }
        }

        return $qstr;
    }

    /**
     * 쿼리스트링 인풋 생성
     * 
     * @return string
     */
    public static function queryStringsInput()
    {
        $qstrInput = null;
        foreach (self::queryStrings() as $key => $value) {
            if (!empty($value)) {
                $qstrInput .= '<input
                    type="hidden"
                    name="'.$key.'"
                    value="'.$value.'"
                    >'.PHP_EOL;
            }
        }

        return $qstrInput;
    }

    /**
     * 테이블 스키마에 있는 컬럼 자동 생성
     * 
     * @param string $needle 생성 방식 선택
     * 
     * @return array
     */
    private static function _schemaColumn($needle = 'COLUMN_COMMENT')
    {
        $sql = "SELECT COLUMN_COMMENT, COLUMN_NAME
                FROM information_schema.COLUMNS
                WHERE TABLE_NAME = '".self::$table."'
                AND TABLE_SCHEMA = '".self::$pdo::$name."'
                ORDER BY ORDINAL_POSITION ASC";
        $list = self::$pdo::query($sql)->fetchAll();

        $cols = [];
        $i = 0;
        foreach ($list as $row) {
            if ($needle === 'COLUMN_COMMENT') {
                $cols[$i] = empty($row['COLUMN_COMMENT']) ?
                    $row['COLUMN_NAME'] : $row['COLUMN_COMMENT'];
            } else {
                $cols[$i]['comment'] = empty($row['COLUMN_COMMENT']) ?
                    $row['COLUMN_NAME'] : $row['COLUMN_COMMENT'];
                $cols[$i]['name'] = $row['COLUMN_NAME'];
            }

            $i += 1;
        }

        return $cols;
    }

    /**
     * 전달 인자 유효성 검사
     * 기본적으로 FILTER_SANITIZE_STRING 방식으로 검사함
     * 
     * @param array $filters 검사할 전달 인자
     * 
     * @return array 검사가 완료된 전달 인자
     */
    protected static function validateVars($filters = null)
    {
        $args = $vars = [];

        if ($filters === null) {
            $cols = self::_schemaColumn('name');
            $filters = [];
            foreach ($cols as $col) {
                $filters[$col['name']] = FILTER_SANITIZE_STRING;
            }
        }

        foreach ($filters as $key => $value) {
            if (isset($_REQUEST[$key])) {
                $args[$key] = $_REQUEST[$key];
            } else {
                unset($filters[$key]);
            }
        }

        $vars = filter_var_array($args, $filters);

        return $vars;
    }

    /**
     * 총 페이지 수
     * 
     * @return int
     */
    private function _totalPage()
    {
        extract(self::queryStrings());

        if ($this->count === -1) {
            $this->count = $this->totalCount();
        }

        $total = ceil($this->count / $rows);

        return $total;
    }

    /**
     * 총 row 수
     * 
     * @param string $sql_count 카운트
     * 
     * @return int
     */
    public function totalCount($sql_count = 'COUNT(*)')
    {
        $sql = "SELECT {$sql_count}
                {$this->common}
                {$this->where}";
        $count = (int)self::$pdo::query($sql)->fetchColumn();

        $this->count = $count;

        return $count;
    }

    /**
     * 게시물 리스트를 생성
     * 
     * @param string $order 정렬방식
     * @param string $limit 게시물 숫자
     * @param PDO    $fetch fetch 방식
     * 
     * @return array
     */
    public function getRows($order = null, $limit = null, $fetch = PDO::FETCH_ASSOC)
    {
        extract(self::queryStrings());

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

    /**
     * 페이징
     * 
     * @return string
     */
    public function paging()
    {
        extract(self::queryStrings());
        $qstr = self::queryString();

        $total = $this->_totalPage();

        $qstr = preg_replace('#&page=[0-9]*#', '', $qstr);
        $qstr = preg_replace('#&amp;page=[0-9]*#', '', $qstr);
        $url = URI.'?'.$qstr.'&amp;page=';
        $str = '<li class="page-item">
                <a class="page-link" href="'.$url.'1">
                <span aria-hidden="true">처음</span>
                <span class="sr-only">처음</span>
                </a>
                </li>'.PHP_EOL;

        $start = $page - floor(self::PAGES / 2);
        if ($start < 1) {
            $start = 1;
        }

        $end = $start + self::PAGES - 1;
        if ($end >= $total) {
            $end = $total;
        }

        if (($end - $start + 1) < self::PAGES) {
            $start = $end - self::PAGES + 1;
            if ($start < 1) {
                $start = 1;
            }
        }

        if ($start > 1) {
            $str .= '<li class="page-item">
                <a class="page-link" href="'.$url.($start - 1).'">이전</a>
                </li>'.PHP_EOL;
        } else {
            $str .= ''.PHP_EOL;
        }

        for ($i = $start; $i <= $end; $i += 1) {
            if ($page != $i) {
                $str .= '<li class="page-item">
                    <a class="page-link" href="'.$url.$i.'">'.$i.'</a>
                    </li>'.PHP_EOL;
            } else {
                $str .= '<li class="page-item active">
                    <a class="page-link" href="#">'.$i.'</a>
                    </li>'.PHP_EOL;
            }
        }

        if ($total > $end) {
            $str .= '<li class="page-item">
                <a class="page-link" href="'.$url.($end + 1).'">다음</a>
                </li>'.PHP_EOL;
        }

        $str .= '<li class="page-item">
            <a class="page-link" href="'.$url.$total.'">
            <span aria-hidden="true">마지막</span>
            <span class="sr-only">마지막</span>
            </a>
            </li>'.PHP_EOL;
        $str = "<ul class=\"pagination\">{$str}</ul>";

        return $str;
    }

    /**
     * 게시물 정렬 앵커 태그 생성
     * 
     * @param string $text  정렬할 컬럼설명
     * @param string $col   정렬할 컬럼명
     * @param string $flag  정렬 방식
     * @param string $class 앵커 태그 class
     * 
     * @return string
     */
    public function orderBy($text, $col, $flag = 'ASC', $class = null)
    {
        if (strpos($col, '.') > 0) {
            $cols = explode('.', $col);
            $property = end($cols);
        } else {
            $property = $col;
        }

        extract(self::queryStrings());
        $qstr = self::queryString();
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

    /**
     * 하나의 레코드를 얻음
     * 
     * @param string $key    컬럼명
     * @param string $value  값
     * @param string $select 얻을 컬럼들
     * 
     * @return array
     */
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

    /**
     * Insert
     * 
     * @param array $arr 입력할 데이터셋
     * 
     * @return object PDOStatement
     */
    public function insert($arr)
    {
        $sql = 'INSERT INTO ' . self::$table . ' SET ';
        $values = [];
        foreach ($arr as $key => $value) {
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

    /**
     * Update
     * 
     * @param array  $arr   업데이트할 데이터셋
     * @param string $where 업데이트할 레코드
     * @param mixed  $index 업데이트할 레코드 index
     * 
     * @return object PDOStatement
     */
    public function update($arr, $where, $index = null)
    {
        $sql = 'UPDATE ' . self::$table . ' SET ';
        $values = [];
        foreach ($arr as $key => $value) {
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

    /**
     * Delete
     * 
     * @param string $where 삭제할 레코드
     * @param mixed  $index 삭제할 레코드 index
     * 
     * @return object PDOStatement
     */
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

    /**
     * 스킨에 표시할 heading 텍스트
     * 기본 값이 없다면 클래스명으로 표기
     * 
     * @return string
     */
    protected function heading()
    {
        return $this->heading ?? ucfirst(self::$namespace);
    }

    /**
     * 테이블 스키마에서 정렬 앵커 태그를 자동으로 생성
     * 
     * @return array
     */
    protected function columnOrderBys()
    {
        $list = self::_schemaColumn(false);
        $cols = [];
        foreach ($list as $row) {
            $cols[$row['name']] = $this->orderBy($row['comment'], $row['name']);
        }

        return $cols;
    }

    /**
     * Skin 설정
     * 
     * @param string $type 리스트, 등록수정
     * 
     * @return string
     */
    private function _skin($type)
    {
        if ($type === 'row') {
            $file = '/'.self::$namespace.'/'.self::$namespace.'-row';
            $skin = '/template/'.SKIN.'/row';
        } elseif ($type === 'rows') {
            $file = '/'.self::$namespace.'/'.self::$namespace.'-list';
            $skin = '/template/'.SKIN.'/list';
        }

        if (is_file(VIEW.$file.'.php')) {
            return $file;
        } else {
            return $skin;
        }
    }

    /**
     * 게시물 리스트 템플릿
     * 
     * @return null
     */
    public function rows()
    {
        $skin = self::_skin('rows');

        $template = [
            'heading' => self::heading(),
            'count' => $this->totalCount(),
            'paging' => $this->paging(),
            'inputs' => $this->queryStringsInput(),
            'list' => $this->getRows(),
            'cols' => $this->columnOrderBys()
        ];

        return Route::template($skin, $template, 'header');
    }

    /**
     * 게시물 리스트 업데이트
     * 
     * @return null
     */
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
        extract(parent::validateVars($filters));

        $qstr = self::queryString();

        if ($req === 'list-delete') {
            $where = " WHERE ( ";
            $cnt = count($ids);
            $i = 0;
            foreach ($ids as $id) {
                $where .= " id = '{$id}' ";

                $i += 1;
                if ($cnt !== $i) {
                    $where .= " OR ";
                }
            }
            $where .= " ) ";

            $this->delete($where);
        } else if ($req === 'list-modify') {
            // foreach($ids as $id) {
            //     $arr = [
            //         'serial' => $serials[$id],
            //         'name' => $names[$id],
            //     ];
            //     $this->update($arr, 'WHERE id = ?', $id);
            // }
        }

        return Route::location('/'.self::$namespace.'?'.$qstr);
    }

    /**
     * 게시물 등록수정 템플릿
     * 
     * @param mixed $id index
     * 
     * @return null
     */
    public function row($id = null)
    {
        $skin = self::_skin('row');

        $template = [
            'heading' => self::heading(),
            'inputs' => self::queryStringsInput(),
            'row' => $this->getRow('id', $id),
            'cols' => self::_schemaColumn('name')
        ];

        return Route::template($skin, $template, 'header');
    }

    /**
     * 게시물 업데이트
     * 
     * @return null
     */
    public function rowUpdate()
    {
        $qstr = self::queryString();
        extract($vars = parent::validateVars());

        if (!empty($id)) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = self::$pdo->lastInsertId();
        }

        return Route::location('/'.self::$namespace.'/row/'.$id.'?'.$qstr);
    }
}
