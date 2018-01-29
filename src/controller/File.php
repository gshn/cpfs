<?php
namespace controller;

use model\FileModel;
use helper\Route;

class File extends FileModel
{
    public function rows()
    {
        extract(self::getQueryVars());
        $qstr = self::getQueryString();

        $count = $this->getTotalCount();
        $paging = $this->getPaging();
        $list = $this->getList();
        $cols = $this->getCols();
        $inputs = $this->getQueryStringInput();

        Route::template('/'.self::$namespace.'/'.self::$namespace.'-list', [
            'stx' => $stx,
            'qstr' => $qstr,
            'count' => $count,
            'paging' => $paging,
            'list' => $list,
            'cols' => $cols,
            'inputs' => $inputs
        ], 'header');
    }

    public function rowsUpdate()
    {
        $filters = [
            'req' => FILTER_SANITIZE_STRING,
            'ids' => [
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_FORCE_ARRAY,
                'options' => [
                    'min_range' => 1
                ]
            ]
        ];
        extract($this->_getVars($filters));

        $qstr = self::getQueryString();

        if ($req === 'list-delete') {
            $where = " WHERE ( ";
            $cnt = count($ids);
            $i = 0;
            foreach($ids as $id) {
                $where .= " id = '{$id}' ";

                $i += 1;
                if ($cnt !== $i) {
                    $where .= " OR ";
                }
            }
            $where .= " ) ";

            $this->delete($where);

            swal('성공!', '일괄 삭제처리 했습니다.', 'success', '/'.self::$namespace.'?'.$qstr);
        } else if ($req === 'list-modify') {
            // foreach($ids as $id) {
            //     $arr = [
            //         'serial' => $serials[$id],
            //         'name' => $names[$id],
            //     ];
            //     $this->update($arr, 'WHERE id = ?', $id);
            // }

            swal('성공', '일괄 수정처리 했습니다.', 'success', '/'.self::$namespace.'?'.$qstr);
        }
    }

    public function row($id = null)
    {
        $qstr = self::getQueryString();
        $row = $this->getRow('id', $id);
        $inputs = self::getQueryStringInput();
        $cols = self::getColumn('name');

        Route::template('/'.self::$namespace.'/'.self::$namespace.'-row', [
            'qstr' => $qstr,
            'row' => $row,
            'inputs' => $inputs,
            'cols' => $cols
        ], 'header');
    }

    public function rowUpdate()
    {
        $qstr = self::getQueryString();
        extract($vars = $this->_getVars());

        if (!empty($id)) {
            $this->update($vars, 'WHERE id = ?', $id);
        } else {
            $this->insert($vars);
            $id = self::$pdo->lastInsertId();
        }

        swal('성공!', '정보를 입력 했습니다.', 'success', '/'.self::$namespace.'/row/'.$id.'?'.$qstr);
    }
}
