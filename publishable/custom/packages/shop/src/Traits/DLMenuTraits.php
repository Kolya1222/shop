<?php

namespace EvolutionCMS\Shop\Traits;

use Illuminate\Support\Facades\Config;
use EvolutionCMS\Shop\Facades\Snippet;

trait DLMenuTraits
{
    public function getMenu(string $configKey)
    {
        $config = Config::get('Doclister.' . $configKey);
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
