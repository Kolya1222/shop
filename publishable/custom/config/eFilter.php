<?php
return [
    'efilter' => [
        'cfg'               => 'custom',
        'css'               => 0,
        'remove_disabled'   => 0
    ],
    'efilterresult' => [
        'api'           => 1,
        'tvList'        => 'price, product_tag',
        'tvPrefix'      => '',
        'display'       => 6,
        'depth'         => 4,
        'paginate'      => 'pages',
        'addWhereList'  => 'c.template = 3',
        'config'        => 'paginate:custom'
    ],
];
