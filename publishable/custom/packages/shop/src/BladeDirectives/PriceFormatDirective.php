<?php

namespace EvolutionCMS\Shop\BladeDirectives;

use Illuminate\Support\Facades\Blade;


class PriceFormatDirective
{
    public static function register()
    {
        Blade::directive('price', function($params) {         
            return '<?php echo EvolutionCMS\Shop\Utils\FormatePriceUtil::formatPrice(' . $params . '); ?>';
        });
    }
}