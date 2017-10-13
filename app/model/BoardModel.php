<?php
require HELPER.'/BoardTrait.php';

class BoardModel extends ModelHelper
{
    use BoardMethod;

    protected $id;
    protected $title;
    protected $contents;
    protected $create_username;
    protected $create_date;
    
    public $fa;

    public function __construct($table)
    {
        parent::__construct($table);
        extract(self::_getQueryVars());

        switch($table) {
            case 'tblnotice':
                $this->fa = 'exclamation';
                break;
            case 'tblevent':
                $this->fa = 'calendar-o';
                break;
            case 'tblfaq':
                $this->fa = 'question';
                break;
        }

        if ($stx) {
            $this->where .= " AND (title LIKE '%{$stx}%' ";
            $this->where .= " OR contents = '{$stx}' ";
            $this->where .= " OR create_username LIKE '%{$stx}%' ";
            $this->where .= " ) ";
        }

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY id DESC";
        }
    }
}
