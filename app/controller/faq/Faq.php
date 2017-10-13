<?php
class Faq extends BoardModel implements ListRow
{
    public function __construct($table = 'tblfaq')
    {
        parent::__construct($table);
    }

    public function push($id)
    {
    }
}
