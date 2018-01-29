<?php
namespace controller;

use model\NoticeModel;
use helper\UploadFile;
use helper\Route;
use controller\File;

class Notice extends NoticeModel
{
    public function rows()
    {
        define('NOTICE', TRUE);

        extract(self::getQueryVars());
        $qstr = self::getQueryString();

        $table_text = $this->table_text ?? ucfirst(self::$namespace);
        $count = $this->getTotalCount();
        $paging = $this->getPaging();
        $inputs = $this->getQueryStringInput();
        $list = $this->getList();

        $cols = [
            'id' => $this->getOrderBy('#', 'id'),
            'title' => $this->getOrderBy('제목', 'title'),
            'datetime' => $this->getOrderBy('등록일시', 'datetime')
        ];

        Route::template('/template/'.SKIN.'/list', [
            'table_text' => $table_text,
            'stx' => $stx,
            'qstr' => $qstr,
            'count' => $count,
            'paging' => $paging,
            'inputs' => $inputs,
            'list' => $list,
            'cols' => $cols
        ], 'header');
    }

    public function row($id = null)
    {
        define('NOTICE', TRUE);
        define('CKEDITOR', TRUE);

        $qstr = self::getQueryString();

        $table_text = $this->table_text ?? ucfirst(self::$namespace);
        $row = $this->getRow('id', $id);
        $inputs = self::getQueryStringInput();

        $cols = [
            '제목' => [
                'type' => 'text',
                'name' => 'title',
                'value' => $row['title'],
                'attribute' => ' required maxlength="100" minlength="2"',
                'placeholder' => '제목을 입력해주세요.'
            ],
            '내용' => [
                'type' => 'textarea',
                'name' => 'content',
                'value' => $row['content'],
                'attribute' => 'required rows="10" maxlength="65535"',
                'placeholder' => '내용을 입력해주세요.'
            ],
            '첨부파일' => [
                'type' => 'file',
                'name' => 'file',
                'value' => $row['file_url'],
                'attribute' => ' accept=".jpg, .jpeg, .png, .gif"',
                'placeholder' => ''
            ]
        ];

        if ((bool) $row['id']) {
            $cols['등록일시'] = [
                'type' => 'datetime-local',
                'name' => '',
                'value' => date('Y-m-d\TH:i', strtotime($row['datetime'])),
                'attribute' => ' readonly',
                'placeholder' => ''
            ];
        }

        Route::template('/template/'.SKIN.'/row', [
            'table_text' => $table_text,
            'qstr' => $qstr,
            'row' => $row,
            'inputs' => $inputs,
            'cols' => $cols
        ], 'header');
    }

    public function rowUpdate()
    {
        $qstr = self::getQueryString();
        extract($vars = $this->_getVars([
            'id' => FILTER_VALIDATE_INT,
            'title' => FILTER_SANITIZE_STRING,
            'content' => FILTER_UNSAFE_RAW
        ]));

        $vars['content'] = $_POST['content'] ?? null;

        $uploadFile = new UploadFile(UPLOAD_PATH.'/notice', UPLOAD.'/notice', $_FILES, ['jpg', 'jpeg', 'gif', 'png']);
        $files = $uploadFile->upload();

        $file = new File();
        foreach ($files as $file_row) {
            if (empty($file_row['error'])) {
                $file->insert($file_row);
                $vars['file_url'] = $file_row['url'];
            }
        }

        $notice = new Notice();
        if (!empty($id)) {
            $notice->update($vars, 'WHERE id = ?', $id);
        } else {
            $notice->insert($vars);
            $id = self::$pdo->lastInsertId();
        }

        swal('성공!', '정보를 저장 했습니다.', 'success', '/'.self::$namespace.'/row/'.$id.'?'.$qstr);
    }
}
