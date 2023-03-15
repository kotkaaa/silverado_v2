<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
	    'cache' => [
			'class' => \yii\caching\FileCache::class,
		    'cachePath' => '@runtime/cache'
	    ]
    ],
];
