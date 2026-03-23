<?php

namespace EvolutionCMS\Shop\Facades;

use EvolutionCMS\Shop\Services\CommercePlaceholderService;
use Illuminate\Support\Facades\Facade;

class GetPlaceholder extends Facade
{

    protected static function getFacadeAccessor()
    {
        return CommercePlaceholderService::class;
    }
}
