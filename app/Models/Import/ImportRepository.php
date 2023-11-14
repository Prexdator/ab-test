<?php

namespace App\Models\Import;

use Nette\Database\Explorer;

class ImportRepository
{

    public function __construct(
        protected Explorer $explorer,
    )
    {
    }

    public function importData(string $typ): string
    {
        $status = 'FAIL';

        if ($typ === 'csv') {
            $url = 'https://www.3it.cz/test/data/xml';

            $reader = new \XMLReader();
            $reader->open($url, 'UTF-8');

            if ($reader->read()) {
                $xml = $reader->readOuterXML();
                $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
                $itemsCount = count($xml->ZAZNAM);

                if ($itemsCount > 0) {

                    foreach ($xml as $item) {
                        $this->explorer->table('users')->insert([
                            'importId' => $item->ID ?? '',
                            'name' => $item->JMENO  ?? '',
                            'surname' => $item->PRIJMENI  ?? '',
                            'date' => $item->DATE  ?? '',
                            'importedDate' => date("Y.m.d"),
                        ]);

                    }

                    $status = 'OK';
                }

            }

        }

        return $status;
    }


}