<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\Import\ImportRepository;
use Nette;


final class ImportsPresenter extends Nette\Application\UI\Presenter
{

    public function __construct(
        public ImportRepository $importRepository,
    )
    {
    }

    public function actionImport(): Nette\Utils\Json
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $typ = $data['typ'];

        $status = $this->importRepository->importData($typ);

        $this->sendJson(['status' => $status]);

    }


}
