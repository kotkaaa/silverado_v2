<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'class' => \common\components\User::class,
            'identityClass' => \common\models\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                // Home page
                '/' => 'site/index',
                // Category page
                'category' => 'category/index',
                'category/<category:[\d\w-]+>' => 'category/index',
                // Product page
                'product/<product:[\d\w-]+>' => 'product/index',
                // Cart
                'cart/add/<product:[\d\w-]+>' => 'cart/add',
                'cart/success/<order:[\d]+>' => 'cart/success',
                'cart/<action:(remove|clear|search-city|search-warehouse)>' => 'cart/<action>',
                'cart' => 'cart/index',
            ],
        ],
        'cart' => [
            'class' => 'common\components\Cart',
            // you can change default storage class as following:
            'storageClass' => [
                'class' => 'yii2mod\cart\storage\SessionStorage'
            ]
        ]
    ],
    'params' => $params,
];
