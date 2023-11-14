<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


final class MigrationPresenter extends Nette\Application\UI\Presenter
{

    public function __construct(
        protected Nette\Database\Explorer $explorer,
    )
    {
    }

    public function actionCreate(): Nette\Utils\Json
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $typ = $data['typ'];
        $status = 'FAIL';
        if ($typ === 'createTable') {

            $create = $this->explorer->query('DROP TABLE IF EXISTS `users`');

            $this->explorer->query("
				CREATE TABLE IF NOT EXISTS `users` (
					    `id` int(200) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'primary key',
                        `importId` int(255) DEFAULT NULL,
                        `name` varchar(50) DEFAULT NULL,
                        `surname` varchar(50) DEFAULT NULL,
                        `date` varchar(80) DEFAULT NULL,
                        `importedDate` varchar(80) DEFAULT NULL,
UNIQUE KEY `id` (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='users'");

            if ($create) {
                $status = 'OK';
            }
        }


        $this->sendJson(['status' => $status]);

    }
}
