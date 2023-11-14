<?php

namespace App\Models\Home;

use Nette\Database\Explorer;

class HomeRepository
{

    public function __construct(
        protected Explorer $explorer,
    )
    {
    }

    public function getData(): array
    {
        $tableData = [];
        $data = $this->explorer->fetchAll('SELECT * FROM users ORDER BY date ASC' );

        if ($data) {
            $tableData = $data;
        }
        return $tableData;
    }


}