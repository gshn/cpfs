<?php
class PushModel extends ModelHelper
{
    protected $id;
    protected $title;
    protected $contents;
    protected $timestamp;

    public function __construct($table = 'tblpush')
    {
        parent::__construct($table);
        extract(self::_getQueryVars());

        if ($stx) {
            $this->where .= " AND (title LIKE '%{$stx}%' ";
            $this->where .= " OR contents LIKE '%{$stx}%' ";
            $this->where .= " ) ";
        }

        if (empty($sst) || empty($sod)) {
            $this->sst = 'id';
            $this->sod = 'DESC';
        }

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY id DESC";
        }
    }
}
