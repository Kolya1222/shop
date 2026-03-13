<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\TemplateController;
use EvolutionCMS\Shop\Traits\DLMenuTraits;
use EvolutionCMS\Shop\Traits\BreadcrumbsTraits;
use EvolutionCMS\Shop\Traits\CartTraits;
use EvolutionCMS\Shop\Traits\CommonDataTraits;
use Illuminate\Support\Facades\Config;

class PageController extends TemplateController
{
    use DLMenuTraits, BreadcrumbsTraits, CartTraits, CommonDataTraits;

    public function process()
    {
        $viewData = $this->getCommonData();
        $viewData['categories'] = $this->getItems('categories_hit');
        $viewData['products'] = $this->getItems('product');
        $this->addViewData($viewData);
    }

    private function getItems($config)
    {
        $params = Config::get('Doclister.'.$config);
        $result = evo()->runSnippet('DocLister', $params)->getDocs();
        $data = $this->formatData($result, $config);
        return $data;
    }

    private function formatData($result, $config)
    {
        $items = [];
        if ($config == 'product') {
            foreach ($result as $item) {
                $items[] = [
                    'id'            => $item['id'],
                    'title'         => $item['pagetitle'],
                    'price'         => $this->formatPrice($item['price']),
                    'product_tag'   => $item['product_tag']
                ];
            }
        } else {
            foreach ($result as $item) {
                $items[] = [
                    'id'    => $item['id'],
                    'title' => $item['pagetitle']
                ];
            }
        }
        return $items;
    }

    private function formatPrice($price): string
    {
        return evo()->runSnippet('PriceFormat', ['price' => $price]);
    }
}
