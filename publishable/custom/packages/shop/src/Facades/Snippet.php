<?php

namespace EvolutionCMS\Shop\Facades;

use Illuminate\Support\Facades\Facade;

class Snippet
{
    /**
     * Любой сниппет
     */
    public static function run(string $name, array $params = [])
    {
        return evo()->runSnippet($name, $params);
    }

    /**
     * Doclister сниппет
     */
    public static function doclister(array $params = [])
    {
        return evo()->runSnippet('Doclister', $params)->getDocs();
    }

    /**
     * DLCrumbs сниппет
     */
    public static function dlcrumbs(array $params = [])
    {
        return evo()->runSnippet('DLCrumbs', $params);
    }

    /**
     * DLCrumbs сниппет
     */
    public static function cart(array $params = [])
    {
        return evo()->runSnippet('Cart', $params);
    }
    
    /**
     * DLMenu сниппет
     */
    public static function dlmenu(array $params = [])
    {
        return evo()->runSnippet('DLMenu', $params)->getMenu()[0];
    }
    
    /**
     * PriceFormat сниппет
     */
    public static function priceformat(array $params = [])
    {
        return evo()->runSnippet('PriceFormat', $params);
    }
    
    /**
     * eFilter сниппет
     */
    public static function efilter(array $params = [])
    {
        return evo()->runSnippet('eFilter', $params);
    }
    
    /**
     * eFilterResult сниппет
     */
    public static function efilterresult(array $params = [])
    {
        return evo()->runSnippet('eFilterResult', $params);
    }
}