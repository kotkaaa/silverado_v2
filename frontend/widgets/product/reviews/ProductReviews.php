<?php

namespace frontend\widgets\product\reviews;

use common\models\Product;
use common\models\Review;
use common\services\ReviewService;
use yii\base\InvalidConfigException;

/**
 * Class ProductReviews
 * @package frontend\widgets\product\reviews
 */
class ProductReviews extends \yii\base\Widget
{
    /** @var Product */
    public $product;

    /** @var ReviewService */
    public $reviewService;

    /**
     * ProductReviews constructor.
     * @param ReviewService $reviewService
     * @param array $config
     */
    public function __construct(ReviewService $reviewService, $config = [])
    {
        parent::__construct($config);
        $this->reviewService = $reviewService;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->product) {
            throw new InvalidConfigException('Product property not set.');
        }

        parent::init();
    }

    /**
     * @return string|void
     */
    public function run()
    {
        return $this->render('index', [
            'model' => new Review(['scenario' => Review::SCENARIO_POST]),
            'dataProvider' => $this->reviewService->getByProduct($this->product),
            'product' => $this->product,
            'average' => $this->reviewService->getAverageRating($this->product)
        ]);
    }
}