<?php

return array(
    'site' => array(
        'name' => 'Qchan Image Host',
        'description' => 'Upload & Share',
        'keywords' => 'images, photos, image hosting, photo hosting, free image hosting',
        'email' => '',
        'copyright' => '&copy; 2015 Quadra Studios.'
    ),
    'parent_site' => array(
        'enabled' => false,
        'name' => '',
        'logo' => '',
        'url' => '',
    ),
    'ui' => array(
        'theme' => 'default',
        'language' => 'en',
    ),
    'upload' => array(
        'size_limit' => '2M',
    ),
    'cdn' => array(
        'enabled' => false,
    ),
    'watermark' => array(
        'enabled' => false,
        'min_size' => '200x200',
        'position' => '10,10',
    ),
);
