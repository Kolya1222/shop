<?php

namespace EvolutionCMS\Shop\Facades;

use EvolutionCMS\Shop\Interfaces\RunSnippetServiceInterface;
use Illuminate\Support\Facades\Facade;

class Snippet extends Facade
{

    protected static function getFacadeAccessor()
    {
        return RunSnippetServiceInterface::class;
    }

    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();
        $params = $arguments[0] ?? [];
        return $instance->run($method, $params);
    }
}
