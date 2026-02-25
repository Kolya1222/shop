<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'name' => array(
        'caption' => 'Характеристика',
        'type' => 'text'
    ),
    'value' => array(
        'caption' => 'Значение',
        'type' => 'text'
    )
);
$settings['templates'] = array(
    'outerTpl' => '<div class="items">[+wrapper+]</div>',
    'rowTpl' => '<div class="item">[+name+] - [+value+]</div>'
);
