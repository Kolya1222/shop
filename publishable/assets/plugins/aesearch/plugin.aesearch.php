<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

if (!function_exists('app') || empty(Config::get('aesearch'))) {
    return;
}

if (evo()->event->name == 'OnLoadSettings') {
    $url = $url ?? '/aesearch';
    Route::post($url, function () {
        $search = request()->input('search');
        if(!request()->ajax() || !is_scalar($search)) evo()->sendErrorPage();

        $_GET['search'] = $search;
        if(empty($_GET['search'])) {
            return ['status' => false];
        }

        $params = Config::get('aesearch');
        evo()->invokeEvent('OnWebPageInit');
        $snippet = $params['snippet'] ?? 'evoSearch';
        unset($params['snippet']);
        $results = evo()->runSnippet($snippet, $params);

        return [
            'results' => $results,
            'status' => !empty($results)
        ];
    });
}
if (evo()->event->name == 'OnLoadWebDocument') {
    evo()->regClientStartupHTMLBlock('<script defer src="' . MODX_SITE_URL . 'assets/plugins/aesearch/aesearch.min.js"></script><link rel="stylesheet" href="' . MODX_SITE_URL . 'assets/plugins/aesearch/aesearch.css">');
}
