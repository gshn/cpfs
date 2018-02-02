<?php
/**
 * Api.php
 * 
 * PHP Version 7
 * 
 * @category Controller
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
namespace controller;

use helper\Route;
use helper\Library;
use helper\Database;
use controller\Notice;

/**
 * Api Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
final class Api
{
    private static $_pdo;

    /**
     * API
     * 
     * @return void
     */
    public function __construct()
    {
        $method = '_'.URIS[2].ucfirst(URIS[3]).ucfirst(URIS[4]);

        if (method_exists($this, $method)) {
            self::$_pdo = new Database();
            $this->$method();
        } else {
            Route::api('없는 메소드 입니다.');
        }

        return null;
    }

    /**
     * 필수 값 체크
     * 
     * @param array $vars required
     * 
     * @return null|array
     */
    private static function _emptyCheck(array $vars)
    {
        $vars = Library::vars($vars);

        foreach ($vars as $key => $value) {
            if (empty($value)) {
                return Route::api('empty '.$key);
            }
        }

        return $vars;
    }

    /**
     * 공지사항 목록
     * GET https://cpfs.gs.hn/api/notice
     * 
     * @return null
     */
    private function _notice()
    {
        $notice = new Notice();
        $list = $notice->getList();

        return Route::api(true, $list);
    }

    /**
     * 공지사항 등록
     * POST https://cpfs.gs.hn/api/notice/row
     * 
     * @return null
     */
    private function _noticeRow()
    {
        $emptyCheck = [
            'title' => FILTER_SANITIZE_STRING,
            'content' => FILTER_SANITIZE_STRING
        ];

        $vars = [
            'file_url' => FILTER_VALIDATE_URL
        ];

        extract(self::_emptyCheck($emptyCheck));
        extract(Library::vars());

        $insert = [
            'title' => $title,
            'content' => $content,
            'file_url' => $file_url
        ];

        $notice = new Notice();
        $result = [
            'result' => $notice->insert($insert)
        ];


        return Route::api(true, $result);
    }
}
