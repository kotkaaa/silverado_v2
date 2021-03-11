<?php

namespace frontend\widgets\selections\bestsellers;

use common\services\ProductService;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Class TopSales
 */
class BestSellers extends \yii\base\Widget
{
    /** @var ProductService */
    public $productService;

    /** @var string */
    public $cacheId;

    /** @var int */
    public const CACHE_LIFETIME = 3600;

    /**
     * BestSellers constructor.
     * @param array $config
     * @param ProductService $productService
     */
    public function __construct($config = [], ProductService $productService)
    {
        parent::__construct($config);
        $this->productService = $productService;
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->cacheId = \Yii::$app->cache->buildKey(self::class);
    }

    /**
     * @return mixed|string
     */
    public function run()
    {
        return \Yii::$app->cache->getOrSet($this->cacheId, function () {
            return $this->render('index', [
                'dataProvider' => new ActiveDataProvider([
                    'query' => $this->productService->find()
                        ->leftJoin('order_product', new Expression('order_product.product_uuid = product.uuid'))
                        ->andHaving(['>', new Expression('count(order_product.uuid)'), 0])
                        ->orderBy(['count(order_product.uuid)' => SORT_DESC])
                        ->groupBy(['product.uuid'])
                ])
            ]);
        }, self::CACHE_LIFETIME);
    }
}