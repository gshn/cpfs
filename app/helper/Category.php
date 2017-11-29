<?php
namespace helper;

trait Category
{
    public function getCategory($cate_id)
    {
        $sql = "SELECT *
                FROM tblcategory
                WHERE id = '{$cate_id}'";
        $row = self::$pdo->query($sql)->fetch();

        return $row;
    }
}
