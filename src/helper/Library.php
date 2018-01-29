<?php
namespace helper;

use \DateTime;

abstract class Library
{
    /**
     * @brief 쿠키 네임을 md5로 암호화 해서 생성함
     * @param string $name 쿠키 네임
     * @param string $value 값
     * @param string $expire strtotime 형식
     * @param string $path 페이지 경로
     * @param string $domain 도메인
     * @param bool $secure HTTPS 연결이라면 true
     * @param bool $httponly http 접속 이외에 접근 불가 쿠키
     * @return bool
     * @see controller/User.php
     * @see /app/core.php
     * @see setcookie()
     */
    public static function setCookie($name, $value, $expire = '+1 month', $path = '/', $domain = DOMAIN, $secure = false, $httponly = false)
    {
        $name = md5($name);

        if ($value === null) {
            unset($_COOKIE[$name]);
            return setcookie($name, null, -1, $path, $domain, $secure, $httponly);
        }

        if (!empty($_SERVER['HTTPS'])) {
            $secure = true;
            $httponly = true;
        }

        return setcookie($name, base64_encode($value), strtotime($expire), $path, $domain, $secure, $httponly);
    }

    /**
     * @brief md5로 암호화 해서 생성된 쿠키 네임의 값을 찾음
     * @param string $name 쿠키 네임
     * @return string | null
     * @see controller/User.php
     * @see /app/core.php
     * @see setCookie()
     */
    public static function getCookie($name)
    {
        $name = md5($name);

        if (array_key_exists($name, $_COOKIE)) {
            return base64_decode($_COOKIE[$name]);
        } else {
            return null;
        }
    }

    /**
     * @brief 날짜와 날짜 사이에 있는 모든 날짜를 반환
     * @param string $first strtotime 형식 시작일
     * @param string $last strtotime 형식 종료일
     * @param string $step strtotime 형식
     * @param string $output_format 출력 형식
     * @return string $dates
     */
    public static function dateRange($first, $last, $step = '+1 day', $output_format = 'Y-m-d')
    {
        $dates = array();
        $current = strtotime($first);
        $destiny = strtotime($last);
        if ($current > $destiny) {
            $current = strtotime($last);
            $destiny = strtotime($first);
        }
        while ($current <= $destiny) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    /**
     * @brief 시간 입력받아 현재로 부터 어떤 시점이었는지 한글로 표현
     * @param string $datetime
     * @param bool $today
     * @return string
     */
    public static function dateStr($datetime, $today = false)
    {
        $diff = TIME - strtotime($datetime);
        if ($diff < 60) {
            if ($today === true) {
                return '오늘';
            }
            return $diff . '초 전';
        } else if (60 <= $diff && $diff < 3600) {
            if ($today === true) {
                return '오늘';
            }
            return round($diff / 60) . '분 전';
        } else if (3600 <= $diff && $diff < 86400) {
            if ($today === true) {
                return '오늘';
            }
            return round($diff / 3600) . '시간 전';
        } else if (86400 <= $diff && $diff < (86400 * 2)) {
            return '어제';
        } else {
            return round($diff / 86400) . '일 전';
        }
    }

    /**
     * @brief 문자열을 원하는 길이만큼 표기하고 뒤는 흐리기 mb_strimwidth 함수 쉽게 래핑
     * @param string $str
     * @param int $len
     * @param string $suffix
     * @return string
     */
    public static function cutStr($str, $len, $suffix = '…')
    {
        return mb_strimwidth($str, 0, $len, $suffix, 'utf-8');
    }

    /**
     * @brief GET, POST로 받은 변수 자동으로 filter_var_array적용
     * @param array $filters
     * @return array $vars
     */
    public static function getVars($filters)
    {
        $args = $vars = [];

        foreach($filters as $key => $value) {
            $args[$key] = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
        }

        $vars = filter_var_array($args, $filters);
        return $vars;
    }

    /**
     * @brief With DateTime you can make the shortest date&time validator for all formats.
     * @author glavic@gmail.com
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function validDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return !($d && $d->format($format) == $date);
    }


    /**
     * @brief 휴대전화 번호 유효성 체크
     * @param string $hp
     * @return bool
     */
    public static function validHpNumber($hp)
    {
        return !(preg_match('/^01[0-9]{8,9}$/', preg_replace('/[^0-9]/', '', $hp)));
    }

    /**
     * @brief 휴대전화 번호에 하이픈 자동 부여
     * @param string $hp
     * @return string
     */
    public static function getHpNumber($hp)
    {
        return preg_replace('/([0-9]{3})([0-9]{3,4})([0-9]{4})$/', '\\1-\\2-\\3', preg_replace('/[^0-9]/', '', $hp));
    }

    public static function uniqueMultidimArray($array, $key)
    {
        $temp_array = [];
        $i = 0;
        $key_array = [];

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i += 1;
        }

        return $temp_array;
    }

    public static function checkState($state = null)
    {
        if ($state === null) {
            $_SESSION['state'] = md5(microtime() . mt_rand());

            return $_SESSION['state'] ?? false;
        } else {
            return !($_SESSION['state'] === $state);
        }
    }
}
