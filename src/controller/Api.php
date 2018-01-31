<?php
namespace controller;

use helper\Route;
use helper\Library;
use helper\Database;
use helper\UploadFile;
use controller\Notice;

final class Api
{
    private static $pdo;
    const CURRENT_VERSION = 12008;

    public function __construct()
    {
        if (method_exists($this, URIS[2])) {
            self::$pdo = new Database();
            $method = URIS[2];
            $this->$method();
        } else {
            Route::api('없는 메소드 입니다.');
        }
    }

    /**
     * 필수 값 체크
     * @param mixed vars
     * @return mixed vars
     */
    private static function emptyCheck($vars)
    {
        $vars = Library::getVars($vars);

        foreach ($vars as $key => $value) {
            if (empty($value)) {
                Route::api('empty '.$key);
            }
        }

        return $vars;
    }

    /**
     * 공지사항 목록
     * GET https://cpfs.gs.hn/api/notice
     * @param int page
     * @return array list
     */
    private function notice()
    {
        $notice = new Notice();
        $list = $notice->getList();

        Route::api(TRUE, $list);
    }
}
