<?php
namespace model;

use helper\Model;

class FileModel extends Model
{
    protected $id;
    protected $realname;
    protected $uniquename;
    protected $realsize;
    protected $readsize;
    protected $path;
    protected $url;
    protected $type;
    protected $datetime;

    public function __construct($table = null)
    {
        parent::__construct($table);
        extract(self::getQueryVars());

        $this->table_text = '파일';

        if (!empty($stx)) {
            $this->where .= " AND (realname LIKE '%{$stx}%' ";
            $this->where .= " OR type LIKE '%{$stx}%' ";
            $this->where .= " ) ";
        }

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY id DESC";
        }
    }
}
