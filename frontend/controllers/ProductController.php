<?php

namespace frontend\controllers;

use common\classes\AutoBind\BindActionParamsTrait;
use common\models\Product;

class ProductController extends \yii\web\Controller
{
    use BindActionParamsTrait;

    /**
     * @param Product $product
     * @return string
     */
    public function actionIndex(Product $product)
    {
        return $this->render('index', [
            'model' => $product
        ]);
    }
}
