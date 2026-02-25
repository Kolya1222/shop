<?php

namespace EvolutionCMS\Shop\Traits;

/**
 * Трейт для использования
 * eFilter
 */
trait FilterTrait
{
	/**
	 * Ставим плейсхолдер efilterForm
	 */
	public function makeFilter()
	{
		evo()->runSnippet('eFilter', [
			'cfg' 				=> 'custom',
			'css' 				=> 0,
			'remove_disabled' 	=> 0
		]);
		return (evo()->getPlaceholder('eFilter_form') ??  '');
	}

	/**
	 * Возвращаем результаты 
	 * в плейсхолдер catalog
	 * а пагинацию в pages
	 */
	public function getFilteredCatalog()
	{
		$json = evo()->runSnippet('eFilterResult', [
			'parents' 		=> evo()->documentObject['id'],
			'api' 			=> 1,
			'tvList' 		=> 'price, new_item, product_tag',
			'tvPrefix' 		=> '',
			'display' 		=> 6,
			'depth' 		=> '4',
			'paginate' 		=> 'pages',
			'addWhereList' 	=> 'c.template = 4',
			'config' 		=> 'paginate:custom'
		]);

		if (!empty($json)) {
			$arr = json_decode($json, 1);
		}
		$products = [];
		foreach ($arr as $item) {
			$products[] = [
				'id' 			=> $item['id'],
				'title' 		=> $item['pagetitle'],
				'price' 		=> EvolutionCMS()->runSnippet('PriceFormat', ['price' => $item['price']]),
				'product_tag' 	=> $item['product_tag']
			];
		}
		$filters = [];
		$filters[] = [
			'products' 	=> $products,
			'pages' 	=> evo()->getPlaceholder('pages'),
		];
		return ($filters[0]);
	}
}
