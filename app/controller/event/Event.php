<?php
class Event extends BoardModel implements ListRow
{
    public function __construct($table = 'tblevent')
    {
        parent::__construct($table);
    }

    public function push($id)
    {
    }
}
