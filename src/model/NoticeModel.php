<?php
namespace model;

use helper\Model;

class NoticeModel extends Model
{
    protected $id;
    protected $title;
    protected $content;
    protected $file_url;
    protected $datetime;

    public function __construct($table =  null)
    {
        parent::__construct($table);
        extract(parent::getQueryVars());

        $this->table_text = '공지사항';

        if (!empty($stx)) {
            $this->where .= " AND (title LIKE '%{$stx}%' ";
            $this->where .= " OR content LIKE '%{$stx}%' ";
            $this->where .= " ) ";
        }

        if (!empty($sst) && !empty($sod)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY id DESC";
        }
    }
}
