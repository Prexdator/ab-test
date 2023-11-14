<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\Home\HomeRepository;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        public HomeRepository $homeRepository
    )
    {
    }

    public function actionTableData(): Nette\Utils\Json
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $typ = $data['typ'] ?? '';
        $tableData = [];


        if($typ === 'showTable')
        {
          $tableData = $this->homeRepository->getData();
        }

        $this->sendJson(['tableData' => $tableData]);
    }
}
