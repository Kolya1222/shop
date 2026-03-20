<?php

namespace EvolutionCMS\Shop\Utils;

use EvolutionCMS\Shop\Facades\Snippet;

class FormatePriceUtil
{
    public static function formatPrice($price, $convert = 0)
    {
        return Snippet::priceformat([
            'price' => $price,
            'convert' => $convert
        ]);
    }
}
