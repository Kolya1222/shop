<?php

namespace EvolutionCMS\Shop\Traits;

/**
 * Трейт для получения
 * Меню (DLMenu)
 */
trait DLMenuTraits
{
    public function getmenu($parents)
    {
        $result = evo()->runSnippet('DLMenu', [
            'parents'       => $parents,
            'maxDepth'      => 1,
            'returnDLObject'=> 1,
        ])->getMenu()[0];
        $menu = [];
        foreach ($result as $item) {
            $menu[] = [
                'id'    => $item['id'],
                'title' => $item['pagetitle']
            ];
        }
        return ($menu);
    }
}
