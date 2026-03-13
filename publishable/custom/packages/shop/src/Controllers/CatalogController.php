<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\TemplateController;
use EvolutionCMS\Shop\Traits\BreadcrumbsTraits;
use EvolutionCMS\Shop\Traits\DLMenuTraits;
use EvolutionCMS\Shop\Traits\CartTraits;
use EvolutionCMS\Shop\Traits\CommonDataTraits;
use EvolutionCMS\Shop\Interfaces\FilterServiceInterface;

class CatalogController extends TemplateController
{
    use BreadcrumbsTraits, DLMenuTraits, CartTraits, CommonDataTraits;

    public function process()
    {
        $filterService = app(FilterServiceInterface::class);
        $viewData = $this->getCommonData();
        $viewData['filter'] = $filterService->renderForm();
        $viewData['filterresult'] = $filterService->getFilteredCatalog(evo()->documentIdentifier);
        $this->addViewData($viewData);
    }
}
