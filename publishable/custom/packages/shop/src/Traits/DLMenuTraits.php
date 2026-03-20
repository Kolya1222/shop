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
        $result = Snippet::dlmenu($config);
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
