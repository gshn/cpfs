<?php
namespace model;

class UserModel extends Model
{
    protected $id;
    protected $account_type;
    protected $password;
    protected $package_name;
    protected $login_id;
    protected $login_pw;
    protected $email;
    protected $nickname;
    protected $hp;
    protected $postzip;
    protected $addr_01;
    protected $addr_02;
    protected $image_name;
    protected $image_url;
    protected $thumb_name;
    protected $thumb_url;
    protected $email_auth_code;
    protected $email_auth_check;
    protected $hp_public_check;
    protected $rate;
    protected $create_date;
    protected $delete_date;
    protected $hidden;
    protected $alarm_rcv_msg;
    protected $alarm_keyword;
    protected $alarm_pick;
    protected $alarm_notice;

    public function __construct($table = 'tbluser')
    {
        parent::__construct($table);
        extract(self::_getQueryVars());

        if ($stx) {
            $this->where .= " AND (login_id = '{$stx}' ";
            $this->where .= " OR nickname = '{$stx}' ";
            $this->where .= " ) ";
        }

        if (!empty($sst) && !empty($sod) && property_exists(get_class($this), $sst)) {
            $this->order = "ORDER BY {$sst} {$sod}";
        } else {
            $this->order = "ORDER BY id DESC";
        }
    }

    public function getRow($key, $value, $package_name = null, $select = '*')
    {
        if ($select !== '*') {
            $this->select = $select;
        }

        if ($package_name !== null) {
            $sql = "SELECT {$this->select}
                    {$this->common}
                    WHERE package_name = '{$package_name}'
                    AND {$key} = ?";
        } else {
            $sql = "SELECT {$this->select}
                    {$this->common}
                    WHERE {$key} = ?";
        }

        $row = self::$pdo::query($sql, [$value])->fetch();

        return $row;
    }
}
