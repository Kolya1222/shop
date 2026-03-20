<?php

namespace EvolutionCMS\Shop\Plugins;

use Illuminate\Support\Facades\Event;

$deliveries = [
    'pickup'  => ['title' => 'Самовывоз', 'price' => 0],
    'post'    => ['title' => 'Почта России', 'price' => 500],
    'courier' => ['title' => 'Доставка курьером', 'price' => 300],
];

$paymets = [
    'upon_receipt' => ['code' => 'on_delivery', 'title' => 'Оплата при получении'],
];

Event::listen(['evolution.OnBeforeCartItemAdding'], function ($params) {
    if (!isset($params['item']['options']) || empty($params['item']['options'])) {
        return;
    }
    $options = $params['item']['options'];
    $basePrice = $params['item']['price'];
    $additionalPrice = 0;
    $selectedOptionsDetails = [];
    foreach ($options as $key => $value) {
        if (strpos($key, 'options_') === 0 && !empty($value)) {
            $optionIndex = (int) substr($key, strlen('options_'));
            $json = json_decode(evo()->runSnippet('MultiTV', [
                'tvName'  => 'options',
                'docid'   => $params['item']['id'],
                'offset'  => $optionIndex - 1,
                'display' => 1,
                'toJson'  => 1,
            ]));

            if (!empty($json) && isset($json[0])) {
                $additionalPrice += (float)$json[0]->value;
                $selectedOptionsDetails[] = [
                    'id'    => $optionIndex,
                    'name'  => $json[0]->name,
                    'price' => (float)$json[0]->value,
                    'key'   => $key,
                    'value' => $value
                ];
            }
        }
    }
    if ($additionalPrice > 0) {
        $params['item']['price'] = $basePrice + $additionalPrice;
        $params['item']['meta'][] = json_encode($selectedOptionsDetails, JSON_UNESCAPED_UNICODE);
    }
});

Event::listen(['evolution.OnBeforeCartItemRemoving'], function ($params) {
    $requestData = $_POST;
    $actionData = isset($requestData['data']['data']) ? $requestData['data']['data'] : [];

    if ($params['by'] == 'row' && !empty($actionData['removeOption'])) {
        $row = $params['row'];
        $removeOptionId = (int)$actionData['removeOption'];
        $cart = ci()->carts->getCart('products');
        $items = $cart->getItems();

        if (isset($items[$row])) {
            $item = $items[$row];

            $metaOptions = [];
            if (!empty($item['meta']) && isset($item['meta'][0])) {
                $metaOptions = json_decode($item['meta'][0], true);
            }

            $simpleOptions = isset($item['options']) ? $item['options'] : [];

            $removedPrice = 0;
            $removedKey = '';

            foreach ($metaOptions as $index => $opt) {
                if ($opt['id'] == $removeOptionId) {
                    $removedPrice = $opt['price'];
                    $removedKey = $opt['key'];
                    unset($metaOptions[$index]);
                    break;
                }
            }

            if ($removedKey && isset($simpleOptions[$removedKey])) {
                unset($simpleOptions[$removedKey]);
            }

            if ($removedPrice > 0) {
                $params['prevent'] = true;
                $newPrice = $item['price'] - $removedPrice;
                $items[$row]['price'] = $newPrice;
                $items[$row]['options'] = $simpleOptions;
                $items[$row]['meta'] = [json_encode(array_values($metaOptions), JSON_UNESCAPED_UNICODE)];
                $cart->setItems($items);
            }
        }
    }
});

Event::listen(['evolution.OnManagerBeforeOrderRender'], function ($params) use ($deliveries) {
    $params['config']['tvList'] = 'product_gallery';
    $params['columns']['image'] = [
        'title' => 'Фото',
        'content' => function ($data, $DL, $eDL) {
            $galleryRaw = isset($data['tv.product_gallery']) ? $data['tv.product_gallery'] : '';

            if (!empty($galleryRaw)) {
                $gallery = json_decode($galleryRaw, true);
                $images = isset($gallery['fieldValue']) ? $gallery['fieldValue'] : $gallery;

                if (!empty($images[0]['image'])) {
                    $src = $images[0]['image'];
                    return '<img src="/' . ltrim($src, '/') . '" style="width:60px; height:auto; border-radius:3px;">';
                }
            }

            return '<i class="fa fa-mobile-alt" style="font-size:24px; color:#ccc;"></i>';
        },
        'sort' => 10,
    ];

    $params['groups']['contact']['fields']['comment'] = [
        'title'   => 'Комментарий клиента',
        'content' => function ($data) {
            if (!empty($data['fields']['comment'])) {
                return nl2br(e($data['fields']['comment']));
            }
            return '<i class="text-muted">Комментарий отсутствует</i>';
        },
        'sort'    => 40,
    ];

    $params['columns']['options'] = [
        'title' => 'Выбранные опции',
        'content' => function ($data, $DL, $eDL) {
            $output = '';
            if (!empty($data['meta'])) {
                $options = json_decode($data['meta'][0], true);
                if (is_array($options)) {
                    foreach ($options as $opt) {
                        $output .= '<div style="font-size: 0.85em; color: #555;">';
                        $output .= '<b>' . $opt['name'] . '</b> (+' . $opt['price'] . ' руб.)';
                        $output .= '</div>';
                    }
                }
            }
            return $output;
        },
        'sort' => 35,
    ];
});

Event::listen(['evolution.OnRegisterDelivery'], function ($params) use ($deliveries) {
    foreach ($deliveries as $key => $data) {
        $params['rows'][$key] = [
            'title' => $data['title'],
            'price' => $data['price'],
            'code'  => $key,
            'markup'=> '',
        ];
    }
});

Event::listen(['evolution.OnRegisterPayments'], function ($params) use ($paymets) {
    foreach ($paymets as $key => $data) {
        $class = new \Commerce\Payments\Payment(evo(), $params);
        evo()->commerce->registerPayment($data['code'], $data['title'], $class);
    }
});

Event::listen(['evolution.OnOrderRawDataChanged'], function ($params) use ($deliveries) {
    $processor = evo()->commerce->loadProcessor();
    $selected = $processor->getCurrentDelivery();
    if ($processor->isOrderStarted() && isset($deliveries[$selected])) {
        $current = $deliveries[$selected];
        $params['rows'][$selected] = [
            'title' => $current['title'],
            'price' => $current['price'],
        ];
    }
});