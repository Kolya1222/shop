<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\Shop\Interfaces\FilterServiceInterface;

class CatalogController extends BaseController
{  
    public function process()
    {
        parent::process();
        $this->filterService = app(FilterServiceInterface::class);
        $this->addViewData('filter', 'filterresult');
    }

    protected function getFilter()
    {
        return $this->filterService->renderForm();
    }
    
    protected function getFilterresult()
    {
        return $this->filterService->getFilteredCatalog(evo()->documentIdentifier);
    }
}