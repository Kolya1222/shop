<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'name' => array(
        'caption' => 'Название',
        'type' => 'text'
    ),
    'image' => array(
        'caption' => 'Картинка',
        'type' => 'image'
    )
);
$settings['templates'] = array(
    'outerTpl' => '<div class="items">[+wrapper+]</div>',
    'rowTpl' => '<div class="item">[+name+] - [+image+]</div>'
);
