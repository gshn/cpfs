<?php
namespace model;

use helper\Model;

class UserModel extends Model
{
    protected $id;
    protected $email;
    protected $password;
    protected $timestamp;
    protected $datetime;

    public function __construct($table = null)
    {
        parent::__construct($table);
        extract(parent::getQueryVars());

        $this->table_text = '회원';

        if (!empty($stx)) {
            $this->where .= " AND (email LIKE '%{$stx}%' ";
            $this->where .= " ) ";
        }

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY id DESC";
        }
    }
}
