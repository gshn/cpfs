<?php
/**
 * COZMO PHP Fire Starter (CPFS)
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2018 cozmogames.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	COZMO PHP Fire Starter (CPFS)
 * @author	COZMO Dev Team (hheo@cozmoworks.com)
 * @copyright Copyright (c) 2018, COZMO, Inc. (http://cozmogames.com)
 * @repository https://github.com/gshn/cpfs
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://cozmogames.com
 * @since	Version 1.8
 */
//require __DIR__.'/../vendor/autoload.php';

/**
 * 개발모드 관련 상수
 * SLOW_QUERY_TIME 밀리초보다 더 걸리는 쿼리를 디버그창에 표기
 * DEV 모드일 경우 모든 에러 리포트 및 사용중인 변수들을 앱 하단에 노출
 */
if ($_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR']) {
    define('DEV', TRUE);
    error_reporting(-1);
    ini_set('display_errors', 1);

} else {
    define('DEV', FALSE);
    error_reporting(0);

}

/**
 * 환경설정파일
 * config.conf 파일 확장자를 .php로 변경해서 사용한다.
 * 전역변수는 $cf 하나만 허용한다.
 */
if (!file_exists(__DIR__.'/../src/config.php')) {
    echo 'Change the config.conf file name to config.php.';
    exit;
}
$cf = require __DIR__.'/../src/config.php';

require __DIR__.'/../src/core.php';
