<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\TemplateController;
use EvolutionCMS\Shop\Traits\BreadcrumbsTraits;
use EvolutionCMS\Shop\Traits\DLMenuTraits;
use EvolutionCMS\Shop\Traits\FilterTrait;
use EvolutionCMS\Shop\Traits\CartTraits;

class CatalogController extends TemplateController
{
    use BreadcrumbsTraits;
    use DLMenuTraits;
    use FilterTrait;
    use CartTraits;
    public function process()
    {
        $data = [
            'breadcrumbs'   => $this->getbreadcrumbs(evo()->documentIdentifier),
            'headermenu'    => $this->getmenu(0),
            'cartheader'    => $this->getCart(),
            'footermenu'    => $this->getmenu(2),
            'filter'        => $this->makeFilter(),
            'filterresult'  => $this->getFilteredCatalog(),
            'footerclient'  => $this->getmenu(41),
        ];
        $this->addViewData($data);
    }
}
