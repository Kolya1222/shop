<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\TemplateController;
use EvolutionCMS\Shop\Traits\BreadcrumbsTraits;
use EvolutionCMS\Shop\Traits\DLMenuTraits;
use EvolutionCMS\Shop\Traits\CartTraits;

class BaseController extends TemplateController
{
    use BreadcrumbsTraits, DLMenuTraits, CartTraits;

    public function process()
    {
        $this->addViewData([
            'headermenu'    => $this->getMenu('headermenu'),
            'footermenu'    => $this->getMenu('footermenu'),
            'footerclient'  => $this->getMenu('footerclient'),
            'cartheader'    => $this->getCart(),
            'breadcrumbs'   => $this->getBreadcrumbs(evo()->documentIdentifier),
        ]);
        if (\Auth::user())
        {
            $this->addViewData([
                'username'  => \Auth::user()->username,
                'email'     => \Auth::user()->email,
                'phone'     => \Auth::user()->phone
            ]);
        }
    }
}