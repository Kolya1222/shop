<?php

namespace EvolutionCMS\Shop\Traits;

use EvolutionCMS\Shop\Facades\Snippet;
use Illuminate\Support\Facades\Config;

trait CartTraits
{
    public function getCart($type = 'mini')
    {
        $globalParams = Config::get('Cart.global');
        $params = [];
        if ($type == 'mini') {
            $params = Config::get('Cart.mini');
        }
        if ($type == 'cart') {
            $params = Config::get('Cart.cart');
        }
        return Snippet::Cart(array_merge($params, $globalParams));
    }
}
