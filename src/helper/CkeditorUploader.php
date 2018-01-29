<?php
namespace helper;

class CKEditorUploader
{
    public function __construct()
    {
        if (isset($_FILES['upload']) && $_FILES['upload']['error'] === 0) {
            self::_uploaded($_FILES['upload']);
        } else {
            swal('실패!', '업로드에 실패 했습니다.', 'danger');
        }
    }

    private static function _uploaded($file)
    {
        $upload_url = UPLOAD.'/ckeditor';
        $upload_path = UPLOAD_PATH.'/ckeditor';

        @mkdir($upload_path, 0755);

        $name = UploadFile::uniqueName($file['name']);
        $tmp_name = $file['tmp_name'];

        $basepath = $upload_path.'/'.basename($name);
        $baseurl = $upload_url.'/'.basename($name);

        @unlink($basepath);
        @move_uploaded_file($tmp_name, $basepath);

        if (isset($_REQUEST['CKEditorFuncNum'])) {
            echo "<script> window.parent.CKEDITOR.tools.callFunction({$CKEditorFuncNum}, '{$baseurl}');</script>;";
        } else {
            Route::api(TRUE, [
                'uploaded' => 1,
                'fileName' => $name,
                'url' => $baseurl
            ]);
        }

        exit;
    }
}
