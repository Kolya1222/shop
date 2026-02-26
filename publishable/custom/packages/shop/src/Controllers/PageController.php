<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\TemplateController;
use EvolutionCMS\Shop\Traits\DLMenuTraits;
use EvolutionCMS\Shop\Traits\BreadcrumbsTraits;
use EvolutionCMS\Shop\Traits\CartTraits;

class PageController extends TemplateController
{
    use DLMenuTraits;
    use BreadcrumbsTraits;
    use CartTraits;
    public function process()
    {
        $data = [
            'breadcrumbs'   => $this->getbreadcrumbs(evo()->documentIdentifier),
            'categories'    => $this->getCategoriesHit(),
            'products'      => $this->getProducts(),
            'headermenu'    => $this->getmenu(0),
            'cartheader'    => $this->getCart(),
            'footermenu'    => $this->getmenu(2),
            'footerclient'  => $this->getmenu(40),
        ];
        $this->addViewData($data);
    }

    private function getCategoriesHit()
    {
        $result = EvolutionCMS()->runSnippet('DocLister', [
            'parents'       => 2,
            'depth'         => 0,
            'tvPrefix'      => '',
            'tvList'        => 'popular_categories',
            'returnDLObject'=> 1,
            'orderBy'       => 'createdon DESC'
        ])->getDocs();
        $categories = [];
        foreach ($result as $item) {
            if ($item['popular_categories']) {
                $categories[] = [
                    'id'    => $item['id'],
                    'title' => $item['pagetitle']
                ];
            }
        }

        return $categories;
    }

    private function getProducts()
    {
        $result = EvolutionCMS()->runSnippet('DocLister', [
            'parents'       => 2,
            'depth'         => 3,
            'tvPrefix'      => '',
            'tvList'        => 'new_item, price, product_tag',
            'returnDLObject'=> 1,
            'orderBy'       => 'createdon DESC'
        ])->getDocs();
        $products = [];
        foreach ($result as $item) {
            if (count($products) >= 4) {
                break;
            }
            if (($item['product_tag'] == "Новый") and ($item['id'] != evo()->documentIdentifier)) {
                $products[] = [
                    'id'            => $item['id'],
                    'title'         => $item['pagetitle'],
                    'price'         => EvolutionCMS()->runSnippet('PriceFormat', ['price' => $item['price']]),
                    'product_tag'   => $item['product_tag']
                ];
            }
        }

        return $products;
    }
}
