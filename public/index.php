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
 * PHP Version 7
 * 
 * @category  PHP
 * @package   CPFS
 * @author    gshn <gs@gs.hn>
 * @copyright 2018 Copyright (c) COZMO, Inc.
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   GIT: 0.2.0
 * @link      https://github.com/gshn/cpfs
 */

/**
 * 개발모드 관련 상수
 * SLOW_QUERY_TIME 밀리초보다 더 걸리는 쿼리를 디버그창에 표기
 * DEV 모드일 경우 모든 에러 리포트 및 사용중인 변수들을 앱 하단에 노출
 */
if ($_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR']) {
    define('DEV', true);
    error_reporting(-1);
    ini_set('display_errors', 1);

} else {
    define('DEV', false);
    error_reporting(0);

}

/**
 * 최소 요구 버전 7.0.0
 */
if (PHP_VERSION_ID < 70000) {
    $error = 'The language server needs at least PHP 7.1 installed and in your
              PATH Version found: ' . PHP_VERSION;
    exit($error);
}

/**
 * 환경설정파일
 * config.conf 파일 확장자를 .php로 변경해서 사용한다.
 * 전역변수는 $cf 하나만 허용한다.
 */
if (!file_exists(__DIR__.'/../src/config.php')) {
    exit('Change the config.conf file name to config.php');
}
$cf = include __DIR__.'/../src/config.php';

require __DIR__.'/../src/core.php';
