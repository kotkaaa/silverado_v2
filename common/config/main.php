<?php
return [
    'name' => 'Silverado jewelry boutique',
    'language' => 'ru-RU',
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
        ]
    ],
];
