<?php

namespace EvolutionCMS\Shop\Services;

use EvolutionCMS\Shop\Interfaces\FilterServiceInterface;
use EvolutionCMS\Shop\Facades\Snippet;
use Illuminate\Support\Facades\Config;

class eFilterService implements FilterServiceInterface
{
    public function renderForm()
    {
        $config = Config::get('eFilter.efilter');
        Snippet::efilter($config);
        return (evo()->getPlaceholder('eFilter_form') ?? '');
    }
    
    public function getFilteredCatalog(int $parentId, array $options = [])
    {
        $defaultOptions = Config::get('eFilter.efilterresult');
        
        $options = array_merge($defaultOptions, $options);
        $options['parents'] = $parentId;
        $json = Snippet::eFilterResult($options);
        return $this->formatCatalogData($json);
    }
    
    private function formatCatalogData(string $json)
    {
        $items = json_decode($json, true) ?? [];
        $products = [];
        foreach ($items as $item) {
            $products[] = [
                'id'            => $item['id'],
                'title'         => $item['pagetitle'],
                'price'         => $item['price'],
                'product_tag'   => $item['product_tag'] ?? ''
            ];
        }
        
        return [
            'products' => $products,
            'pages'    => evo()->getPlaceholder('pages')
        ];
    }
}