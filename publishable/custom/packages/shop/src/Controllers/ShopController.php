<?php

namespace EvolutionCMS\Shop\Controllers;

use EvolutionCMS\TemplateController;
use EvolutionCMS\Shop\Traits\DLMenuTraits;
use EvolutionCMS\Shop\Traits\CartTraits;
use EvolutionCMS\Shop\Traits\CommonDataTraits;
use EvolutionCMS\Shop\Traits\BreadcrumbsTraits;

class ShopController extends TemplateController
{
    use DLMenuTraits, CartTraits, CommonDataTraits, BreadcrumbsTraits;

    public function process()
    {
        $viewData = $this->getCommonData();
        $viewData['cart'] = $this->getCart('cart');
        $viewData['order'] = $this->getOrder();
        $this->addViewData($viewData);
    }
    private function getOrder()
    {
        $result = evo()->runSnippet('Order', [
            'cartName'      => 'products',
            'errorTpl'      => '@CODE:<div class="invalid-feedback">[+message+]</div>',
            'errorClass'    => 'is-invalid',
            'requiredClass' => 'is-invalid',
            'templatePath'  => 'views/',
            'formTpl'       => '@B_FILE:cart/form_order',
            'deliveryTpl'   => '@B_FILE:cart/delivery',
            'deliveryRowTpl'=> '@B_FILE:cart/delivery_row',
            'paymentsRowTpl'=> '@B_FILE:cart/payments_row',
            'paymentsTpl'   => '@B_FILE:cart/payments',
            'reportTpl'     => '@B_FILE:cart/report_home',
            'ccSenderTpl'   => '@B_FILE:cart/report',
            'ccSender'      =>'1',
            'ccSenderField' =>'email',
            'rules' => [
                'name'      => [
                    'required'  => 'Введите имя',
                ],
                'email'     => [
                    'required'  => 'Введите email',
                    'email'     => 'Неверная почта',
                ],
                'phone'     => [
                    'required'  => 'Введите номер телефона',
                    'phone'     => 'Неверный телефон',
                ],
                'agreement' => [
                    'required'  => 'Покупка не возможна без согласия',
                    'agreement' => 'Должно быть включено',
                ],
            ],

            'protectSubmit' => 0,
            'submitLimit'   => 0,
        ]);
        return ($result);
    }
}