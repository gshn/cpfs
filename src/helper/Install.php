<?php
/**
 * Install.php
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
 * Install Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
class Install
{
    public static $pdo;

    /**
     * 인스톨 생성자
     * 회원 테이블 create
     * 관리자 insert
     * 공지사항 테이블 create
     * 파일 테이블 create
     *
     * @return null
     */
    public function __construct()
    {
        self::$pdo = new Database();
        self::_createUserTable();
        self::_insertUser();
        self::_createNoticeTable();
        self::_createFileTable();

        return null;
    }

    /**
     * Function _createUserTable
     * 
     * @return null
     */
    private static function _createUserTable()
    {
        $sql = "CREATE TABLE `user` (
                `id` int(10) UNSIGNED NOT NULL COMMENT '#',
                `email` varchar(100) COLLATE utf8mb4_unicode_ci
                    NOT NULL COMMENT '이메일',
                `password` varchar(41) COLLATE utf8mb4_unicode_ci
                    NOT NULL COMMENT '비밀번호',
                `timestamp` timestamp
                    NOT NULL DEFAULT current_timestamp()
                    ON UPDATE current_timestamp() COMMENT '로그인일시',
                `datetime` datetime
                    NOT NULL DEFAULT current_timestamp() COMMENT '등록일시'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        self::$pdo->query($sql);

        $sql = "ALTER TABLE `user`
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `email` (`email`)";
        self::$pdo->query($sql);

        $sql = "ALTER TABLE `user`
                MODIFY `id` int(10) UNSIGNED
                    NOT NULL AUTO_INCREMENT COMMENT '#'";
        self::$pdo->query($sql);

        echo '회원 테이블을 생성 했습니다.<br>';

        return null;
    }

    /**
     * Function _insertUser
     * 
     * @return null
     */
    private static function _insertUser()
    {
        $sql = "INSERT INTO user
                SET email = ?,
                    password = PASSWORD(?)";

        $insert = [
            'super@czm.kr', '1234'
        ];

        self::$pdo->query($sql, $insert);

        echo 'super@czm.kr / 1231 사용자 등록.<br>';

        return null;
    }

    /**
     * Function _createNoticeTable
     * 
     * @return null
     */
    private static function _createNoticeTable()
    {
        $sql = "CREATE TABLE `notice` (
                `id` int(10) UNSIGNED
                    NOT NULL COMMENT '#',
                `title` varchar(100) COLLATE utf8mb4_unicode_ci
                    NOT NULL COMMENT '제목',
                `content` text COLLATE utf8mb4_unicode_ci
                    NOT NULL COMMENT '내용',
                `file_url` varchar(200) COLLATE utf8mb4_unicode_ci
                    NOT NULL COMMENT '파일URL',
                `datetime` timestamp
                    NOT NULL DEFAULT current_timestamp() COMMENT '등록일시'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        self::$pdo->query($sql);

        $sql = "ALTER TABLE `notice`
                ADD PRIMARY KEY (`id`)";
        self::$pdo->query($sql);

        $sql = "ALTER TABLE `notice`
                MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '#'";
        self::$pdo->query($sql);

        echo '공지사항 테이블을 생성 했습니다.<br>';

        return null;
    }

    /**
     * Function _createFileTable
     * 
     * @return null
     */
    private static function _createFileTable()
    {
        $sql = "CREATE TABLE `file` (
                `id` int(10) UNSIGNED NOT NULL COMMENT '#',
                `realname` varchar(255) NOT NULL COMMENT '실제파일명',
                `uniquename` varchar(100) NOT NULL COMMENT '고유파일명',
                `realsize` varchar(50) NOT NULL COMMENT '실제사이즈',
                `readsize` varchar(10) NOT NULL COMMENT '사이즈',
                `path` varchar(255) NOT NULL COMMENT '경로',
                `url` varchar(255) NOT NULL COMMENT 'URL',
                `type` varchar(30) NOT NULL COMMENT '타입',
                `datetime` timestamp
                    NOT NULL DEFAULT current_timestamp() COMMENT '등록일시'
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        self::$pdo->query($sql);

        $sql = "ALTER TABLE `file`
                ADD PRIMARY KEY (`id`)";
        self::$pdo->query($sql);

        $sql = "ALTER TABLE `file`
                MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '#'";
        self::$pdo->query($sql);

        echo '파일 테이블을 생성 했습니다.<br>';
    }
}
