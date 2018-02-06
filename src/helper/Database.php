<?php
/**
 * Database.php
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

/**
 * Database Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
class Database
{
    public static $pdo;
    public static $name;
    public static $user;
    public static $host;

    /**
     * 데이터베이스 커넥터
     * PDO 연결 방식을 이용한 DB 커넥션을 수행 후 DB 커넥터 객체를 리턴
     * 
     * @param string $name 데이터베이스 네임
     * @param string $user 데이터베이스 유저
     * @param string $pass 데이터베이스 패스워드
     * @param string $host 데이터베이스 호스트 서버네임
     * 
     * @return object PDO
     */
    public function __construct(
        $name = null, $user = null, $pass = null, $host = null
    ) {
        if (empty($name) || empty($user) || empty($pass) || empty($host)) {
            $cf = config();
            $name = $cf['db']['name'];
            $user = $cf['db']['user'];
            $pass = $cf['db']['pass'];
            $host = $cf['db']['host'];
        }

        self::$name = $name;
        self::$user = $user;
        self::$host = $host;

        $dsn = "mysql:host={$host};dbname={$name};port=3306;charset=utf8mb4";
        $opt = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false
        ];
        if (DEV) {
            $opt[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }
        self::$pdo = new PDO($dsn, $user, $pass, $opt);

        return self::$pdo;
    }

    /**
     * SQL Query
     * PDO 연결 방식을 이용한 SQL Query 수행
     * DEV 모드일 경우 해당 쿼리의 수행 시간을 표시하는 기능이 있음
     * 
     * @param string $sql    SQL문
     * @param array  $params placeholder 매개변수
     * 
     * @return object PDOStatement
     */
    public static function query($sql, $params = null)
    {
        if (DEV) {
            $stime = microtime_float();
        }

        if ($params === null) {
            $rst = self::$pdo->query($sql);
        } else {
            $rst = self::$pdo->prepare($sql);
            $rst->execute($params);
        }

        if (DEV) {
            $cf = config();
            $etime = microtime_float() - $stime;
            $cf['debug']['query_time'] += $etime;
            $cf['debug']['query_log'] .= 'sql: '.$sql.PHP_EOL
                .round($etime, 4).PHP_EOL.PHP_EOL;
        }

        return $rst;
    }

    /**
     * 가장 최근 세션에 auto_increament로 증가한 primary key return
     * 
     * @return int|null
     */
    public static function lastInsertId()
    {
        return self::$pdo->lastInsertId();
    }
}
