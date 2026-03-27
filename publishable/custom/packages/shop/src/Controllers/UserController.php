<?php

namespace EvolutionCMS\Shop\Controllers;

class UserController extends BaseController
{
    public function process()
    {
        parent::process();
        $this->addViewData('history');
    }

    protected function getHistory()
    {
        return(evo()->runSnippet('History',[
            'tvList' => '6',
        ]));
    }
}