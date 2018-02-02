<?php
/**
 * UserModel.php
 * 
 * PHP Version 7
 * 
 * @category Model
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
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
        extract(parent::queryStrings());

        $this->heading = '회원';

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
