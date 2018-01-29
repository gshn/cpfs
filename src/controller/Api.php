<?php
namespace controller;

use helper\Route;
use helper\Library;
use helper\Database;
use helper\UploadFile;

use controller\Notice;
use controller\Contact;
use controller\File;
use controller\Photo;

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
     * 앱 실행 로그
     * POST http://admin.lillycover.com/api/userLog
     * @param int user_id(필수)
     * @return object {
            "result": true
        }
     */
    private function userLog()
    {
        extract(self::emptyCheck([
            'user_id' => FILTER_VALIDATE_INT
        ]));

        $sql = 'INSERT INTO app_user_log
                SET user_id = ?';
        self::$pdo->query($sql, [$user_id]);

        Route::api(TRUE, [
            'result' => TRUE
        ]);
    }

    /**
     * 공지사항 목록
     * GET http://admin.lillycover.com/api/noticeList
     * @param int page
     * @return array list
     * @example [
            {
                "id": "2",
                "subject": "앱을 출시했씁니다",
                "content": "<p>http://lillycover-admin.czm/api/userLog?user_id=1</p>\r\n\r\n<p><img src=\"http://lillycover.czm/image/notice/369e0f368574ce7606f386ec39db38339ee9846e.png\" style=\"height:1263px; width:691px\" /></p>\r\n",
                "timestamp": "2018-01-23 17:37:40"
            },
            {
                "id": "1",
                "subject": "앱을 출시했씁니다",
                "content": "<p>http://lillycover-admin.czm/api/userLog?user_id=1</p>\r\n\r\n<p><img src=\"http://lillycover.czm/image/notice/369e0f368574ce7606f386ec39db38339ee9846e.png\" style=\"height:1263px; width:691px\" /></p>\r\n",
                "timestamp": "2018-01-23 17:37:05"
            }
        ]
     */
    private function noticeList()
    {
        $notice = new Notice();
        $list = $notice->getList();

        Route::api(TRUE, $list);
    }

    /**
     * 고객문의사항 
     * POST http://admin.lillycover.com/api/contact
     * @param int user_id(필수)
     * @param string email(필수)
     * @param string request(필수)
     * @return object {
            "result": true
        }
     */
    private function contact()
    {
        $vars = self::emptyCheck([
            'user_id' => FILTER_VALIDATE_INT,
            'email' => FILTER_SANITIZE_STRING,
            'request' => FILTER_SANITIZE_STRING,
        ]);

        $contact = new Contact();
        $contact->insert($vars);

        $id = self::$pdo->lastInsertId();

        $message = "
        email: {$email}
        request: {$request}
        ".URL."/contact/row/{$id}
        ";

        $add_header = "From: sunhee@lillycover.com";
        $mail = mb_send_mail('lillycoverlife@gmail.com', '[lillycover] 새로운 고객문의사항이 등록되었습니다.', $message, $add_header);

        Route::api(TRUE, [
            'result' => TRUE
        ]);
    }

    /**
     * 피부사진저장
     * POST http://admin.lillycover.com/api/userPhoto
     * @param array photo 5장 (필수)
     * @param int user_id(필수)
     * @param int skin_id(필수)
     * @param string memo
     * @return array photo
     * @example {
            "user_id": 1,
            "skin_id": 287,
            "memo": "아하",
            "photo_1_id": "6",
            "photo_1_url": "http://lillycover-admin.czm/upload/88e5e6587109ba70a0a7bd87ca2315d778ce587f.jpg",
            "photo_2_id": "7",
            "photo_2_url": "http://lillycover-admin.czm/upload/136c5a00bb75cb77eb321617593f13d378d4452d.jpg",
            "photo_3_id": "8",
            "photo_3_url": "http://lillycover-admin.czm/upload/d7eade82043c92a6b4ec437f472f748633bb5882.jpg",
            "photo_4_id": "9",
            "photo_4_url": "http://lillycover-admin.czm/upload/65f62665a7e46c4403a8f6c2f701f811ce1dc3ef.jpg",
            "photo_5_id": "10",
            "photo_5_url": "http://lillycover-admin.czm/upload/9772e1946e67a7412b76d3a9a1749ce916106a2b.jpg"
        }
     */
    private function userPhoto()
    {
        extract(self::emptyCheck([
            'user_id' => FILTER_VALIDATE_INT,
            'skin_id' => FILTER_VALIDATE_INT
        ]));

        extract(Library::getVars([
            'memo' => FILTER_SANITIZE_STRING
        ]));

        $uploadFile = new UploadFile();
        $files = $uploadFile->upload();

        $error = [];
        $i = 0;
        foreach ($files as $file) {
            if (!empty($file['error'])) {
                Route::api($file['error']);
            }
            $i += 1;
        }

        if ($i !== 1 && $i !== 5) {
            Route::api('사진은 1장 또는 5장만 올릴 수 있어요.');
        }

        $vars = [
            'user_id' => $user_id,
            'skin_id' => $skin_id,
            'memo' => $memo
        ];

        $photoFile = new File();
        $i = 1;
        foreach ($files as $file) {
            $photoFile->insert($file);
            $vars['photo_'.$i.'_id'] = self::$pdo->lastInsertId();
            $vars['photo_'.$i.'_url'] = $file['url'];
            $i += 1;
        }

        $photo = new Photo();
        $photo->insert($vars);

        Route::api(TRUE, $vars);
    }

    /**
     * 피부사진목록
     * GET http://admin.lillycover.com/api/photo
     * @param int user_id(필수)
     * @param int page
     * @param array dates
     * @return array list
     * @example [
        {
            "id": "2",
            "user_id": "1",
            "skin_id": "280",
            "photo_1_id": "6",
            "photo_2_id": "7",
            "photo_3_id": "8",
            "photo_4_id": "9",
            "photo_5_id": "10",
            "photo_1_url": "http://lillycover-admin.czm/upload/88e5e6587109ba70a0a7bd87ca2315d778ce587f.jpg",
            "photo_2_url": "http://lillycover-admin.czm/upload/136c5a00bb75cb77eb321617593f13d378d4452d.jpg",
            "photo_3_url": "http://lillycover-admin.czm/upload/d7eade82043c92a6b4ec437f472f748633bb5882.jpg",
            "photo_4_url": "http://lillycover-admin.czm/upload/65f62665a7e46c4403a8f6c2f701f811ce1dc3ef.jpg",
            "photo_5_url": "http://lillycover-admin.czm/upload/9772e1946e67a7412b76d3a9a1749ce916106a2b.jpg",
            "memo": "아하",
            "datetime": "2018-01-24 18:04:56",
            "user_nick": "릴리커버",
            "user_email": "super@lillycover.com",
            "skin_moisture": "0",
            "skin_oil": "15",
            "skin_wrinkle": "0",
            "skin_flush": "75",
            "skin_type": "DBTV"
        },
        {
            "id": "1",
            "user_id": "1",
            "skin_id": "280",
            "photo_1_id": "1",
            "photo_2_id": "2",
            "photo_3_id": "3",
            "photo_4_id": "4",
            "photo_5_id": "5",
            "photo_1_url": "http://lillycover-admin.czm/upload/105d2f08d209b8de7eff663327b5a78d68cbe91d.jpg",
            "photo_2_url": "http://lillycover-admin.czm/upload/de5a188bb58d4e44b6e7fa5304680f9f9491603c.jpg",
            "photo_3_url": "http://lillycover-admin.czm/upload/49734af63713dec2c47b727f9363106a81b8aac4.jpg",
            "photo_4_url": "http://lillycover-admin.czm/upload/29ec7f1af1fe23950a0028f733239607210982c3.jpg",
            "photo_5_url": "http://lillycover-admin.czm/upload/fd89400fa260ec12a321ddea7d71d3010a9c11b2.jpg",
            "memo": "",
            "datetime": "2018-01-24 18:04:46",
            "user_nick": "릴리커버",
            "user_email": "super@lillycover.com",
            "skin_moisture": "0",
            "skin_oil": "15",
            "skin_wrinkle": "0",
            "skin_flush": "75",
            "skin_type": "DBTV"
        }
    ]
     */
    private function photo()
    {
        extract(self::emptyCheck([
            'user_id' => FILTER_VALIDATE_INT
        ]));

        extract(Library::getVars([
            'dates' => [
                'filter' => FILTER_SANITIZE_STRING,
                'flags'  => FILTER_FORCE_ARRAY
            ]
        ]));

        $photo = new Photo();

        $photo->where .= " AND photo.user_id = '{$user_id}' ";
        $photo->where .= ' AND (';
        foreach ($dates as $index => $date) {
            if (Library::validDate($date, 'Y-m-d')) {
                unset($dates[$index]);
            } else {
                $photo->where .= " DATE(datetime) = '{$date}' ";
                if (end($dates) !== $date) {
                    $photo->where .= ' OR ';
                }
            }
        }
        $photo->where .= ') ';

        $list = $photo->getList();

        Route::api(TRUE, $list);
    }

    /**
     * 앱 버전 체크
     * GET http://admin.lillycover.com/api/checkVersion
     * @param int version(필수)
     * @return {
            "result": false | true
        }
     */
    private function checkVersion()
    {
        extract(self::emptyCheck([
            'version' => FILTER_VALIDATE_INT
        ]));

        if (self::CURRENT_VERSION <= $version) {
            $res = TRUE;
        } else {
            $res = FALSE;
        }

        Route::api(TRUE, [
            'result' => $res
        ]);
    }
}
