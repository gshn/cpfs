<?php
/**
 * File.php
 * 
 * PHP Version 7
 * 
 * @category Controller
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
namespace controller;

use model\FileModel;

/**
 * File Class
 * 
 * @category Class
 * @package  CPFS
 * @author   gshn <gs@gs.hn>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/gshn/cpfs
 */
class File extends FileModel
{
    /**
     * API
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        return null;
    }
}
