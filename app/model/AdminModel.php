<?php
class AdminModel extends ModelHelper
{
    protected $id;
    protected $email;
    protected $hp;
    protected $name;
    protected $password;
    protected $datetime;
    protected $timestamp;
    protected $isdelete;

    public function __construct()
    {
        parent::__construct();
    }
}
