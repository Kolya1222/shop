<?php

namespace EvolutionCMS\Shop\Traits;

trait CommonDataTraits
{
    public function getCommonData(): array
    {
        return [
            'headermenu'   => $this->getmenu(0),
            'cartheader'   => $this->getCart(),
            'breadcrumbs'  => $this->getbreadcrumbs(evo()->documentIdentifier),
            'footermenu'   => $this->getmenu(2),
            'footerclient' => $this->getmenu(9),
        ];
    }
}
