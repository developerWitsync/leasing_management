<?php
/*
 * Secret key and Site key get on https://www.google.com/recaptcha
 * */
return [
    'secret' => env('CAPTCHA_SECRET', '6LeyC5oUAAAAAN2o_6TEkJBW2Q7cIeCVuhbRSBUZ'),
    'sitekey' => env('CAPTCHA_SITEKEY', '6LeyC5oUAAAAAPpTg4QuZWetfq01_c6y3sxtqv2k'),
    /**
     * @var string|null Default ``null``.
     * Custom with function name (example customRequestCaptcha) or class@method (example \App\CustomRequestCaptcha@custom).
     * Function must be return instance, read more in folder ``examples``
     */
    'request_method' => customRequestCaptcha(),
    'options' => [
        'multiple' => false,
        'lang' => app()->getLocale(),
    ],
    'attributes' => [
        'theme' => 'light'
    ],
];