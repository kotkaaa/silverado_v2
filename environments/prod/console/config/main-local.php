<?php
return [
	'components' => [
		'cache' => [
			'class' => \yii\caching\FileCache::class,
			'cachePath' => '@runtime/cache'
		]
	]
];
