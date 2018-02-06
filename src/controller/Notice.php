<?php
/**
 * Notice.php
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

use model\NoticeModel;
use helper\UploadFile;
use helper\Route;
use controller\File;

/**
 * Notice Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
class Notice extends NoticeModel
{
    /**
     * 공지사항 목록
     * helper\Model
     * 
     * @return null|string
     */
    public function rows()
    {
        $template = [
            'heading' => parent::heading(),
            'count' => $this->totalCount(),
            'paging' => $this->paging(),
            'inputs' => $this->queryStringsInput(),
            'list' => $this->getRows(),
            'cols' => [
                'id' => $this->orderBy('#', 'id'),
                'title' => $this->orderBy('제목', 'title'),
                'datetime' => $this->orderBy('등록일시', 'datetime')
            ]
        ];

        return Route::template('/template/'.SKIN.'/list', $template, 'header');
    }

    /**
     * 공지사항 등록 / 수정
     * ckeditor 모듈
     * helper\Model
     * 
     * @param int $id 글번호
     * 
     * @return null|string
     */
    public function row($id = null): ?string
    {
        define('CKEDITOR', true);

        $row = $this->getRow('id', $id);

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

        $template = [
            'heading' => parent::heading(),
            'inputs' => parent::queryStringsInput(),
            'row' => $row,
            'cols' => $cols
        ];

        return Route::template('/template/'.SKIN.'/row', $template, 'header');
    }

    /**
     * 공지사항 업데이트
     * helper\Model
     * 
     * @return void
     */
    public function rowUpdate()
    {
        $qstr = parent::queryString();
        $validateVars = [
            'id' => FILTER_VALIDATE_INT,
            'title' => FILTER_SANITIZE_STRING,
            'content' => FILTER_UNSAFE_RAW
        ];
        extract($vars = parent::validateVars($validateVars));

        $vars['content'] = $_POST['content'] ?? null;

        $uploadFile = new UploadFile(
            UPLOAD_PATH.'/notice', UPLOAD.'/notice', $_FILES,
            ['jpg', 'jpeg', 'gif', 'png']
        );
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
            $id = parent::$pdo->lastInsertId();
        }

        return Route::location('/'.self::$namespace.'/row/'.$id.'?'.$qstr);
    }
}
