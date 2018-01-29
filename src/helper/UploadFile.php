<?php
namespace helper;

class UploadFile
{
    public static $files;
    public static $allowedExtension;
    public static $path;
    public static $url;

    public function __construct($path = null, $url = null, $files = null, $allowedExtension = ['jpg', 'jpeg', 'gif', 'png'])
    {
        if ($path === null) {
            self::$path = UPLOAD_PATH;
        } else {
            self::$path = $path;
        }

        if ($url === null) {
            self::$url = UPLOAD;
        } else {
            self::$url = $url;
        }

        if ($files === null) {
            self::$files = $_FILES;
        } else {
            self::$files = $files;
        }

        self::$allowedExtension = $allowedExtension;

        @mkdir(self::$path, 0755);
    }

    public static function checkExtension($name)
    {
        $names = explode('.', $name);
        $ext = array_pop($names);

        if (!in_array(strtolower($ext), self::$allowedExtension)) {
            return '허용되지 않는 확장자 입니다.';
        }

        return TRUE;
    }

    public static function checkError($error)
    {
        if ($error === UPLOAD_ERR_OK) {
            return TRUE;
        }

        switch ($error) {
            case UPLOAD_ERR_INI_SIZE :
            case UPLOAD_ERR_FORM_SIZE :
                $msg = '업로드 파일은 '.ini_get('upload_max_filesize').'byte를 넘을 수 없습니다.';
                break;
            case UPLOAD_ERR_PARTIAL :
                $msg = '파일이 일부분만 전송되었습니다.\\n다시 시도 해주세요.';
                break;
            case UPLOAD_ERR_NO_FILE :
                $msg = '파일이 전송되지 않았습니다.\\n다시 시도 해주세요.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR :
                $msg = '임시 폴더가 없습니다.\\n관리자에게 문의 바랍니다.';
                break;
            case UPLOAD_ERR_CANT_WRITE :
                $msg = '디스크에 파일 쓰기를 실패했습니다.\\n관리자에게 문의 바랍니다.';
                break;
            case UPLOAD_ERR_EXTENSION :
                $msg = '확장에 의해 파일 업로드가 중지되었습니다.\\n관리자에게 문의 바랍니다.';
                break;
            default :
                $msg = '알 수 없는 에러로 업로드가 실패했습니다.\\n관리자에게 문의 바랍니다.';
                break;
        }

        return $msg;
    }

    /**
     * @brief 파일명에 특수 문자 제거
     * @param string $name
     * @return string
     */
    public static function getFilename($name)
    {
        return preg_replace('/["\'<>=#&!%\\\\(\)\*\+\?]/', '', $name);
    }

    /**
     * @brief 파일사이즈 문자열로 얻기
     * @param int $size
     * @return string
     */
    public static function readSize($size)
    {
        $kb = round(($size / 1024), 1) .'Kb';
        $mb = round(($size / 1048576), 1) .'Mb';
        $gb = round(($size / 1073741824), 1) .'Gb';
        $tb = round((($size / 1073741824) / 1024), 1) .'Tb';

        if ($tb >= 1) {
            return $tb;
        } else if ($gb >= 1) {
            return $gb;
        } else if ($mb >= 1) {
            return $mb;
        } else if ($kb >= 1) {
            return $kb;
        } else {
            return (int)$size.'b';
        }
    }

    // 파일명에서 특수문자 제거
    public static function safeName($name)
    {
        if (mb_detect_encoding($name, 'UTF-8', TRUE) === false) {
            $name = utf8_encode($name);
        }

        return preg_replace('/["\'<>=#&!%\\\\(\)\*\+\?]/', '', $name);
    }

    // 파일명 치환
    public static function uniqueName($name)
    {
        $uniqid = uniqid();
        $uniquename = sha1($uniqid.$_SERVER['REMOTE_ADDR'].$name);

        $names = explode('.', $name);
        $ext = array_pop($names);
        if (!empty($ext)) {
            $uniquename .= '.'.$ext;
        }

        return $uniquename;
    }

    public function upload()
    {
        if (self::$files === null) {
            return ['error' => '파일이 첨부되지 않았습니다.'];
        }

        $list = [];
        $i = 0;
        foreach (self::$files as $key => $file) {
            if (!is_array($file['error'])) {
                $file['name'] = (array) $file['name'];
                $file['type'] = (array) $file['type'];
                $file['tmp_name'] = (array) $file['tmp_name'];
                $file['error'] = (array) $file['error'];
                $file['size'] = (array) $file['size'];
            }

            foreach ($file['error'] as $index => $error) {
                $list[$i]['key'] = $key;

                $msg = self::checkError($error);
                if ($msg !== TRUE) {
                    $list[$i]['error'] = $msg;
                    $i += 1;
                    continue;
                }

                $realname = self::safeName($file['name'][$index]);
                $msg = self::checkExtension($realname);
                if ($msg !== TRUE) {
                    $list[$i]['error'] = $msg;
                    $i += 1;
                    continue;
                }

                $realsize = $file['size'][$index];
                if ($realsize < 1) {
                    $list[$i]['error'] = '파일 사이즈가 너무 작습니다.';
                    $i += 1;
                    continue;
                }

                $uniquename = self::uniqueName($realname);
                $path = self::$path.'/'.$uniquename;
                @unlink($basepath);

                if (move_uploaded_file($file['tmp_name'][$index], $path)) {
                    $list[$i]['realname'] = $realname;
                    $list[$i]['uniquename'] = $uniquename;
                    $list[$i]['realsize'] = $realsize;
                    $list[$i]['readsize'] = self::readSize($realsize);
                    $list[$i]['path'] = $path;
                    $list[$i]['url'] = self::$url.'/'.$uniquename;
                    $list[$i]['type'] = $file['type'][$index];
                    $i += 1;
                } else {
                    $list[$i]['error'] = '업로드에 실패했습니다.';
                    $i += 1;
                    continue;
                }
            }
        }

        return $list;
    }
}
