<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\TemplateController;
use EvolutionCMS\Shop\Traits\DLMenuTraits;
use EvolutionCMS\Shop\Traits\CartTraits;

class ShopController extends TemplateController
{
    use DLMenuTraits;
    use CartTraits;
    public function process()
    {
        $data = [
            'headermenu'    => $this->getmenu(0),
            'cartheader'    => $this->getCart(),
            'cart'          => $this->getCart('cart'),
            'footermenu'    => $this->getmenu(2),
            'footerclient'  => $this->getmenu(41),
        ];
        $this->addViewData($data);
    }
}