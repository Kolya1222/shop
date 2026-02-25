<?php

namespace EvolutionCMS\Shop\Traits;

/**
 * Трейт для получения
 * Корзины
 */
trait CartTraits
{
    public function getCart($type = 'mini'){
        $globalParams = [
            'templatePath' => 'views/',
            'instance' => 'products',
            'noneWrapOuter' => 1,
            'tvPrefix' => '',
        ];
        $params = [];
        if ($type == 'mini') {
            $params = [
                'ownerTpl' => '@B_FILE:cart/headercart',
            ];
        }
        if ($type == 'cart') {
            $params = [
                'ownerTpl' => '@B_FILE: cart/wrapTpl',
                'tpl' => '@B_FILE: cart/rowTpl',
                'tvList' => ['product_gallery'],
            ];
        }
        return evo()->runSnippet('Cart', array_merge($params, $globalParams));
    }
}
