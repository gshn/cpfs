<?php
/**
 * Benchmark.php
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

/**
 * Benchmark Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
class Benchmark
{
    private $_benchmarkResult;

    /**
     * Function __construct
     * 2분안에 결과 도출해야함
     * 여러 곳에서 테스트 해본 결과 평균적으로 10초를 넘기지 않음
     * 
     * @return null
     */
    public function __construct()
    {
        set_time_limit(120);
        $cf = config();

        $settings['db.host'] = $cf['db']['host'];
        $settings['db.user'] = $cf['db']['user'];
        $settings['db.pw'] = $cf['db']['pass'];
        $settings['db.name'] = $cf['db']['name'];

        $this->_benchmarkResult = self::_testBenchmark($settings);

        return null;
    }

    /**
     * Function _testBenchmark
     * 
     * @param array $settings 데이터베이스 정보
     * 
     * @return array
     */
    private static function _testBenchmark($settings)
    {
        $timeStart = microtime(true);

        $result = [];
        $result['version'] = '1.1';
        $result['sysinfo']['time'] = date("Y-m-d H:i:s");
        $result['sysinfo']['php_version'] = PHP_VERSION;
        $result['sysinfo']['platform'] = PHP_OS;
        $result['sysinfo']['server_name'] = $_SERVER['SERVER_NAME'];
        $result['sysinfo']['server_addr'] = $_SERVER['SERVER_ADDR'];

        self::_testMath($result);
        self::_testString($result);
        self::_testLoops($result);
        self::_testIfelse($result);
        if (isset($settings['db.host'])) {
            self::_testMysql($result, $settings);
        }

        $result['total'] = self::_timerDiff($timeStart);

        return $result;
    }

    /**
     * Function _testMath
     * 
     * @param array $result 테스트 결과
     * @param int   $count  실행 횟수
     * 
     * @return null
     */
    private static function _testMath(&$result, $count = 99999)
    {
        $timeStart = microtime(true);

        $mathFunctions = [
            "abs", "acos", "asin", "atan", "bindec", "floor",
            "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt"
        ];
        for ($i = 0; $i < $count; $i++) {
            foreach ($mathFunctions as $function) {
                call_user_func_array($function, array($i));
            }
        }
        $result['benchmark']['math'] = self::_timerDiff($timeStart);

        return null;
    }

    /**
     * Function _testString
     * 
     * @param array $result 테스트 결과
     * @param int   $count  실행 횟수
     * 
     * @return null
     */
    private static function _testString(&$result, $count = 99999)
    {
        $timeStart = microtime(true);

        $stringFunctions = [
            "addslashes", "chunk_split", "metaphone", "strip_tags",
            "md5", "sha1", "strtoupper", "strtolower", "strrev",
            "strlen", "soundex", "ord"
        ];

        $string = 'the quick brown fox jumps over the lazy dog';
        for ($i = 0; $i < $count; $i++) {
            foreach ($stringFunctions as $function) {
                call_user_func_array($function, array($string));
            }
        }
        $result['benchmark']['string'] = self::_timerDiff($timeStart);

        return null;
    }

    /**
     * Function _testLoops
     * 
     * @param array $result 테스트 결과
     * @param int   $count  실행 횟수
     * 
     * @return null
     */
    private static function _testLoops(&$result, $count = 999999)
    {
        $timeStart = microtime(true);
        for ($i = 0; $i < $count; ++$i) {

        }
        $i = 0;
        while ($i < $count) {
            ++$i;
        }
        $result['benchmark']['loops'] = self::_timerDiff($timeStart);

        return null;
    }

    /**
     * Function _testIfelse
     * 
     * @param array $result 테스트 결과
     * @param int   $count  실행 횟수
     * 
     * @return null
     */
    private static function _testIfelse(&$result, $count = 999999)
    {
        $timeStart = microtime(true);
        for ($i = 0; $i < $count; $i++) {
            if ($i == -1) {

            } elseif ($i == -2) {

            } else if ($i == -3) {

            }
        }
        $result['benchmark']['ifelse'] = self::_timerDiff($timeStart);

        return null;
    }

    /**
     * Function _testMysql
     * 
     * @param array $result   테스트 결과
     * @param int   $settings 데이터베이스 접속정보
     * 
     * @return array
     */
    private static function _testMysql(&$result, $settings)
    {
        $timeStart = microtime(true);

        $link = mysqli_connect(
            $settings['db.host'], $settings['db.user'], $settings['db.pw']
        );
        $result['benchmark']['mysql']['connect'] = self::_timerDiff($timeStart);

        //$arr_return['sysinfo']['mysql_version'] = '';

        mysqli_select_db($link, $settings['db.name']);
        $result['benchmark']['mysql']['select_db'] = self::_timerDiff($timeStart);

        $dbResult = mysqli_query($link, 'SELECT VERSION() as version;');
        $arr_row = mysqli_fetch_array($dbResult);
        $result['sysinfo']['mysql_version'] = $arr_row['version'];
        $result['benchmark']['mysql']['query_version'] = self::_timerDiff(
            $timeStart
        );

        $query = "SELECT BENCHMARK(1000000,ENCODE('hello',RAND()));";
        $dbResult = mysqli_query($link, $query);
        $result['benchmark']['mysql']['query_benchmark'] = self::_timerDiff(
            $timeStart
        );

        mysqli_close($link);

        $result['benchmark']['mysql']['total'] = self::_timerDiff($timeStart);

        return $result;
    }

    /**
     * Function _timerDiff
     * 
     * @param string $timeStart 시작시간
     * 
     * @return string
     */
    private static function _timerDiff($timeStart)
    {
        return number_format(microtime(true) - $timeStart, 3);
    }

    /**
     * Function _arrayToHtml
     * 
     * @param string|string $array 시작시간
     * 
     * @return string
     */
    private static function _arrayToHtml($array)
    {
        $result = '';
        if (is_array($array)) {
            $result .= '<table class="table table-bordered">';
            foreach ($array as $k => $v) {
                $result .= "\n<tr><td>";
                $result .= '<strong>' . htmlentities($k) . "</strong></td><td>";
                $result .= self::_arrayToHtml($v);
                $result .= "</td></tr>";
            }
            $result .= "\n</table>";
        } else {
            $result = htmlentities($array);
        }
        return $result;
    }

    /**
     * Function __destruct
     * 
     * @return null
     */
    public function __destruct()
    {
        $template = [
            'html' => self::_arrayToHtml($this->_benchmarkResult)
        ];
        return Route::template('/template/benchmark', $template, 'no-header');
    }
}
