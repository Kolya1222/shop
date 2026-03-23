<?php

namespace EvolutionCMS\Shop\Services;

class CommercePlaceholderService
{
    public function get(array $names)
    {
        $result = [];
        foreach ($names as $key => $name) {
            $result[$key] = evo()->getPlaceholder($name);
        }
        return $result;
    }
}
