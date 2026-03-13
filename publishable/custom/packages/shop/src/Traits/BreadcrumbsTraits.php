<?php

namespace EvolutionCMS\Shop\Traits;

trait BreadcrumbsTraits
{
    public function getbreadcrumbs()
    {
        $result = evo()->runSnippet('DLCrumbs', [
            'showCurrent'   => 1,
            'hideMain'      => 0,
            'config'        => 'crumbs:custom'
        ]);
        return ($result);
    }
}
