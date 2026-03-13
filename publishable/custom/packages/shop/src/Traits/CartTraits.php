<?php

namespace EvolutionCMS\Shop\Traits;

trait CartTraits
{
    public function getCart($type = 'mini')
    {
        $globalParams = [
            'templatePath'  => 'views/',
            'instance'      => 'products',
            'noneWrapOuter' => 1,
            'tvPrefix'      => '',
        ];
        $params = [];
        if ($type == 'mini') {
            $params = [
                'ownerTpl' => '@B_FILE:cart/headercart',
            ];
        }
        if ($type == 'cart') {
            $params = [
                'ownerTpl'  => '@B_FILE: cart/cart_wrap',
                'tpl'       => '@B_FILE: cart/cart_row',
                'tvList'    => ['product_gallery'],
            ];
        }
        return evo()->runSnippet('Cart', array_merge($params, $globalParams));
    }
}
