<?php
class Notice extends BoardModel implements ListRow
{
    public function __construct($table = 'tblnotice')
    {
        parent::__construct($table);
    }

    public function push($id)
    {
    }
}
