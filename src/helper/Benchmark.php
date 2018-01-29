<?php
namespace helper;

class Benchmark
{
    private $benchmarkResult;

    public function __construct()
    {
        set_time_limit(120);
        $cf = config();

        $options['db.host'] = $cf['db']['host'];
        $options['db.user'] = $cf['db']['user'];
        $options['db.pw'] = $cf['db']['pass'];
        $options['db.name'] = $cf['db']['name'];

        $this->benchmarkResult = self::testBenchmark($options);
    }

    private static function testBenchmark($settings)
    {
        $timeStart = microtime(true);

        $result = array();
        $result['version'] = '1.1';
        $result['sysinfo']['time'] = date("Y-m-d H:i:s");
        $result['sysinfo']['php_version'] = PHP_VERSION;
        $result['sysinfo']['platform'] = PHP_OS;
        $result['sysinfo']['server_name'] = $_SERVER['SERVER_NAME'];
        $result['sysinfo']['server_addr'] = $_SERVER['SERVER_ADDR'];

        self::testMath($result);
        self::testString($result);
        self::testLoops($result);
        self::testIfelse($result);
        if (isset($settings['db.host'])) {
            self::testMysql($result, $settings);
        }

        $result['total'] = self::timerDiff($timeStart);
        return $result;
    }

    private static function testMath(&$result, $count = 99999)
    {
        $timeStart = microtime(true);

        $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
        for ($i = 0; $i < $count; $i++) {
            foreach ($mathFunctions as $function) {
                call_user_func_array($function, array($i));
            }
        }
        $result['benchmark']['math'] = self::timerDiff($timeStart);
    }

    private static function testString(&$result, $count = 99999)
    {
        $timeStart = microtime(true);
        $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");

        $string = 'the quick brown fox jumps over the lazy dog';
        for ($i = 0; $i < $count; $i++) {
            foreach ($stringFunctions as $function) {
                call_user_func_array($function, array($string));
            }
        }
        $result['benchmark']['string'] = self::timerDiff($timeStart);
    }

    private static function testLoops(&$result, $count = 999999)
    {
        $timeStart = microtime(true);
        for ($i = 0; $i < $count; ++$i) {

        }
        $i = 0;
        while ($i < $count) {
            ++$i;
        }
        $result['benchmark']['loops'] = self::timerDiff($timeStart);
    }

    private static function testIfelse(&$result, $count = 999999)
    {
        $timeStart = microtime(true);
        for ($i = 0; $i < $count; $i++) {
            if ($i == -1) {

            } elseif ($i == -2) {

            } else if ($i == -3) {

            }
        }
        $result['benchmark']['ifelse'] = self::timerDiff($timeStart);
    }

    private static function testMysql(&$result, $settings)
    {
        $timeStart = microtime(true);

        $link = mysqli_connect($settings['db.host'], $settings['db.user'], $settings['db.pw']);
        $result['benchmark']['mysql']['connect'] = self::timerDiff($timeStart);

        //$arr_return['sysinfo']['mysql_version'] = '';

        mysqli_select_db($link, $settings['db.name']);
        $result['benchmark']['mysql']['select_db'] = self::timerDiff($timeStart);

        $dbResult = mysqli_query($link, 'SELECT VERSION() as version;');
        $arr_row = mysqli_fetch_array($dbResult);
        $result['sysinfo']['mysql_version'] = $arr_row['version'];
        $result['benchmark']['mysql']['query_version'] = self::timerDiff($timeStart);

        $query = "SELECT BENCHMARK(1000000,ENCODE('hello',RAND()));";
        $dbResult = mysqli_query($link, $query);
        $result['benchmark']['mysql']['query_benchmark'] = self::timerDiff($timeStart);

        mysqli_close($link);

        $result['benchmark']['mysql']['total'] = self::timerDiff($timeStart);
        return $result;
    }

    private static function timerDiff($timeStart)
    {
        return number_format(microtime(true) - $timeStart, 3);
    }

    private static function arrayToHtml($array)
    {
        $result = '';
        if (is_array($array)) {
            $result .= '<table class="table table-bordered">';
            foreach ($array as $k => $v) {
                $result .= "\n<tr><td>";
                $result .= '<strong>' . htmlentities($k) . "</strong></td><td>";
                $result .= self::arrayToHtml($v);
                $result .= "</td></tr>";
            }
            $result .= "\n</table>";
        } else {
            $result = htmlentities($array);
        }
        return $result;
    }

    public function __destruct()
    {
        Route::template('/template/benchmark', [
            'html' => self::arrayToHtml($this->benchmarkResult)
        ], 'no-header');
    }
}
