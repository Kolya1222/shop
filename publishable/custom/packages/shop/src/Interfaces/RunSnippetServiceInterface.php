<?php

namespace EvolutionCMS\Shop\Interfaces;

interface RunSnippetServiceInterface
{
    public function run(string $name, $params = []);
    public function __call($name, $arguments);
}
