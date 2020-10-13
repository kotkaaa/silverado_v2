<?php
return [
    'name' => 'Silverado jewelry boutique',
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Kiev',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'formatter' => [
            'currencyCode' => 'UAH'
        ],
        'delivery' => [
            'class' => \common\components\delivery\NovaPoshta::class,
            'key' => '1b5e7e40cc71dbcaa034bd877a9f97b7'
        ],
        'sms' => [
            'class' => \common\components\Turbosms::class,
            'viewPath' => '@frontend/views/sms',
            'sender' => 'SILVERADO',
            'login' => 'fcdkkillthemall',
            'password' => '2c4uk915'
        ]
    ],
];
