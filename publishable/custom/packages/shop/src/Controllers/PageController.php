<?php

namespace EvolutionCMS\Shop\Controllers;

use Illuminate\Support\Facades\Config;
use EvolutionCMS\Shop\Facades\Snippet;

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
        $data = Snippet::docLister($params);
        $result = $this->formatData($data, $config);
        return $result;
    }

    private function getPlaceholders()
    {
        return [
            'order_id'      => evo()->getPlaceholder('commerce_order.id'),
            'order_name'    => evo()->getPlaceholder('commerce_order.name'),
            'order_phone'   => evo()->getPlaceholder('commerce_order.phone'),
            'order_email'   => evo()->getPlaceholder('commerce_order.email'),
            'order_amount'  => evo()->getPlaceholder('commerce_order.amount'),
            'order_currency'=> evo()->getPlaceholder('commerce_order.currency'),
            'payment_id'    => evo()->getPlaceholder('commerce_payment.id'),
            'payment_amount'=> evo()->getPlaceholder('commerce_payment.amount'),
        ];
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
