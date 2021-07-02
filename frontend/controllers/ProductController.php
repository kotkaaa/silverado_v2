<?php

namespace frontend\controllers;

use common\classes\AutoBind\BindActionParamsTrait;
use common\models\Product;
use common\services\ReviewService;

/**
 * Class ProductController
 * @package frontend\controllers
 */
class ProductController extends \yii\web\Controller
{
    use BindActionParamsTrait;

    /** @var ReviewService */
    public $reviewService;

    /**
     * ProductController constructor.
     * @param $id
     * @param $module
     * @param ReviewService $reviewService
     * @param array $config
     */
    public function __construct($id, $module, ReviewService $reviewService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->reviewService = $reviewService;
    }

    /**
     * @param Product $product
     * @return string
     */
    public function actionIndex(Product $product)
    {
        if ($this->reviewService->post($product)) {
            \Yii::$app->session->setFlash('reviewSuccess', 'Дякуємо за відгук! Ваша думка безцінна для нас');
        }

        return $this->render('index', [
            'model' => $product
        ]);
    }
}
