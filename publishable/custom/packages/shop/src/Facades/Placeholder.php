<?php

namespace EvolutionCMS\Shop\Facades;

use EvolutionCMS\Shop\Services\PlaceholderService;
use Illuminate\Support\Facades\Facade;

class Placeholder extends Facade
{

    protected static function getFacadeAccessor()
    {
        return PlaceholderService::class;
    }
}
