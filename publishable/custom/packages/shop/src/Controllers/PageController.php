<?php

namespace EvolutionCMS\Shop\Controllers;

use Illuminate\Support\Facades\Config;
use EvolutionCMS\Shop\Facades\Snippet;
use EvolutionCMS\Shop\Facades\GetPlaceholder;

class PageController extends BaseController
{
    public function process()
    {
        parent::process();
        $this->addViewData([
            'categories'    => $this->getItems('categories_hit'),
            'products'      => $this->getItems('product'),
            'placeholders'  => $this->getPlaceholders()
        ]);
    }

    private function getItems($config)
    {
        $params = Config::get('Doclister.' . $config);
        $data = Snippet::DocLister($params)->getDocs();
        $result = $this->formatData($data, $config);
        return $result;
    }

    private function getPlaceholders()
    {
        $config = Config::get('Commerce');
        $result = GetPlaceholder::get($config);
        return $result;
    }

    private function formatData($result, $config)
    {
        $items = [];
        if ($config == 'product') {
            foreach ($result as $item) {
                $items[] = [
                    'id'            => $item['id'],
                    'title'         => $item['pagetitle'],
                    'price'         => $item['price'],
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
}
