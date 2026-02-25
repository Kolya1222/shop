<?php

use Commerce\Controllers\Traits;

class CustomLangDocLister extends site_contentDocLister
{
    use Traits\CustomTemplatesPathTrait, Traits\CustomLangTrait;

    public function __construct($modx, $cfg = [], $startTime = null)
    {
        $cfg = $this->initializeCustomTemplatesPath($cfg);
        $cfg['lang'] = $modx->commerce->getCurrentLang();

        parent::__construct($modx, $cfg, $startTime);
        $this->handleCustomLang();
    }
}
