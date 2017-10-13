<?php
class DatabaseHelper
{
    static $pdo;
    static $name;
    static $user;
    static $host;

    /**
     * @brief 데이터베이스 커넥터
     * PDO 연결 방식을 이용한 DB 커넥션을 수행 후 DB 커넥터 객체를 리턴
     * @author hheo (hheo@cozmoworks.com)
     * @param string $name 데이터베이스 네임
     * @param string $user 데이터베이스 유저
     * @param string $pass 데이터베이스 패스워드
     * @param string $host 데이터베이스 호스트 서버네임
     * @return PDO
     * @see /app/common.php
     */
    public function __construct($name, $user, $pass, $host)
    {
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
     * @brief SQL Query
     * PDO 연결 방식을 이용한 SQL Query 수행
     * @author hheo (hheo@cozmoworks.com)
     * @param string $sql SQL문
     * @param array $params placeholder 매개변수
     * @return PDOStatement
     * @see DEV 모드일 경우 해당 쿼리의 수행 시간을 표시하는 기능이 있음
     * DEV는 config.php에 정의 되어 있음
     */
    public static function query($sql, $params = null)
    {
        global $cf;

        if (DEV) {
            $stime = microtime_float();
            if ($params === null) {
                $rst = self::$pdo->query($sql);
            } else {
                $rst = self::$pdo->prepare($sql);
                $rst->execute($params);
            }
            $etime = microtime_float() - $stime;
            $cf['query_time'] += $etime;
            if(round($etime, 4) > SQL_SLOW_TIME) {
                $cf['query_debug'] .= '<div class="alert alert-info">'.$sql.'<br>'.round($etime, 4).'</div>'.PHP_EOL;
            }
        } else {
            if ($params === null) {
                $rst = self::$pdo->query($sql);
            } else {
                $rst = self::$pdo->prepare($sql);
                $rst->execute($params);
            }
        }

        return $rst;
    }

    public static function quote($stx)
    {
        return self::$pdo->quote($stx);
    }

    public static function lastInsertId()
    {
        return self::$pdo->lastInsertId();
    }

    public static function getDatabaseName()
    {
        return self::$name;
    }
}
