<?php

namespace EvolutionCMS\Shop\Traits;

use EvolutionCMS\Shop\Facades\Snippet;
use Illuminate\Support\Facades\Config;

trait DLMenuTraits
{
    public function getMenu($parents)
    {
        $config = Config::get('Doclister.dlmenu');
        $config['parents'] = $parents;
        $result = Snippet::DLMenu($config)->getMenu()[0];
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
