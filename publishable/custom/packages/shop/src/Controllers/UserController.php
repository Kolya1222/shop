<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\Shop\Facades\Snippet;

class UserController extends BaseController
{
    public function process()
    {
        parent::process();
        $this->addViewData('history');
    }

    protected function getHistory()
    {
        return Snippet::History([
            'tvList' => '6',
        ]);
    }
}