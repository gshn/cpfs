<?php
require HELPER.'/BoardTrait.php';

class QnaModel extends ModelHelper
{
    use BoardMethod;

    protected $id;
    protected $user_id;
    protected $parent_id;
    protected $title;
    protected $contents;
    protected $create_date;
    protected $res_title;
    protected $res_contents;
    protected $res_username;
    protected $res_date;

    public function __construct($table)
    {
        parent::__construct($table);
        extract(self::_getQueryVars());

        $this->select = "qna.*, usr.login_id, usr.email, usr.nickname";
        $this->common = "FROM tblqna AS qna
                        LEFT JOIN tbluser AS usr
                        ON (qna.user_id = usr.id)";

        $this->where .= " AND (qna.parent_id = 0) ";

        if ($stx) {
            $this->where .= " AND (qna.title LIKE '%{$stx}%' ";
            $this->where .= " OR qna.contents = '{$stx}' ";
            $this->where .= " OR qna.res_username LIKE '%{$stx}%' ";
            $this->where .= " OR usr.login_id LIKE '%{$stx}%' ";
            $this->where .= " OR usr.nickname LIKE '%{$stx}%' ";
            $this->where .= " OR usr.email LIKE '%{$stx}%' ";
            $this->where .= " ) ";
        }

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY qna.id DESC";
        }
    }

    public function getReplyList($parent_id)
    {
        $this->where = " WHERE (qna.parent_id = {$parent_id}) ";
        $this->order = "ORDER BY qna.id ASC";

        return $this->getList();
    }
}
