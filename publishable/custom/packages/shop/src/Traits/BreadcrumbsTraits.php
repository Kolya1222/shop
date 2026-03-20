<?php

namespace EvolutionCMS\Shop\Traits;

use EvolutionCMS\Shop\Facades\Snippet;
use Illuminate\Support\Facades\Config;

trait BreadcrumbsTraits
{
    public function getBreadcrumbs()
    {
        $config = Config::get('Doclister.breadcrumbs');
        $result = Snippet::dlcrumbs($config);
        return ($result);
    }
}
