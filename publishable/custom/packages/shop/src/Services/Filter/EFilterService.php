<?php

namespace EvolutionCMS\Shop\Services\Filter;

use EvolutionCMS\Shop\Interfaces\FilterServiceInterface;

class EFilterService implements FilterServiceInterface
{
    public function renderForm()
    {
        evo()->runSnippet('eFilter', [
            'cfg'               => 'custom',
            'css'               => 0,
            'remove_disabled'   => 0
        ]);
        return (evo()->getPlaceholder('eFilter_form') ?? '');
    }
    
    public function getFilteredCatalog(int $parentId, array $options = [])
    {
        $defaultOptions = [
            'api'           => 1,
            'tvList'        => 'price, product_tag',
            'tvPrefix'      => '',
            'display'       => 6,
            'depth'         => 4,
            'paginate'      => 'pages',
            'addWhereList'  => 'c.template = 3',
            'config'        => 'paginate:custom'
        ];
        
        $options = array_merge($defaultOptions, $options);
        $options['parents'] = $parentId;
        
        $json = evo()->runSnippet('eFilterResult', $options);
        
        if (empty($json)) {
            return ['products' => [], 'pages' => ''];
        }
        
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