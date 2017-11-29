<?php
/**
 * COZMO PHP Fire Starter (CPFS)
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 cozmogames.com
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
 * @copyright Copyright (c) 2017, COZMO, Inc. (http://cozmogames.com)
 * @repository https://github.com/gshn/cpfs
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://cozmogames.com
 * @since	Version 1.7
 */
require __DIR__.'/../app/core.php';

use helper\Library;

/**
 * 개발 모드일 경우 전역변수들 디버깅
 */
if (DEV) {
    if ($_GET) {
        echo '<h5>$_GET</h5>';
        printr($_GET);
    }
    if ($_POST) {
        echo '<h5>$_POST</h5>';
        printr($_POST);
    }
    if ($_FILES) {
        echo '<h5>$_FILES</h5>';
        printr($_FILES);
    }
    echo '<h5>$_SERVER</h5>';
    printr($_SERVER);
    echo '<h5>$_COOKIE</h5>';
    printr($_COOKIE);
    echo '<h5>$_SESSION</h5>';
    printr($_SESSION);
    echo '<h5>In Time</h5>';
    echo $cf['query_debug'];
    echo '<div class="alert alert-warning"><strong>Total SQL Query Time </strong>'.round($cf['query_time'], 4).'</div>';

    echo '<div class="alert alert-danger"><strong>Page rendered in</strong> '.round(microtime_float() - $_SERVER['REQUEST_TIME_FLOAT'], 4).'</div>';
}
