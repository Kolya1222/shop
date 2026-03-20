<?php

namespace EvolutionCMS\Shop\Controllers;

class ShopController extends BaseController
{
    public function process()
    {
        parent::process();
        $this->addViewData('carts', 'payments', 'deliveries');
    }

    protected function getPayments()
    {
        return ci()->commerce->getPayments();
    }

    protected function getDeliveries()
    {
        return ci()->commerce->getDeliveries();
    }

    protected function getCarts()
    {
        return $this->getCart('cart');
    }
}