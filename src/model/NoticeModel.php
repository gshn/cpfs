<?php
/**
 * NoticeModel.php
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

/**
 * NoticeModel
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
class NoticeModel extends Model
{
    protected $id;
    protected $title;
    protected $content;
    protected $file_url;
    protected $datetime;

    /**
     * Function __construct
     * 
     * @param string $table 태이블명
     * 
     * @return null
     */
    public function __construct($table =  null)
    {
        parent::__construct($table);
        extract(parent::queryStrings());

        $this->heading = '공지사항';

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

        return null;
    }
}
