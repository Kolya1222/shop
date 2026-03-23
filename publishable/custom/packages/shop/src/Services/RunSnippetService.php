<?php

namespace EvolutionCMS\Shop\Services;

use EvolutionCMS\Shop\Interfaces\RunSnippetServiceInterface;

class RunSnippetService implements RunSnippetServiceInterface
{
    public function run(string $name, $params = [])
    {
        return evo()->runSnippet($name, $params);
    }
    
    public function __call($name, $arguments)
    {
        return $this->run($name, $arguments[0] ?? []);
    }
}