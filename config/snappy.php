<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => 'xvfb-run /usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => 'xvfb-run /usr/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
);
