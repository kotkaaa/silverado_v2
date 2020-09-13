<?php

namespace frontend\controllers;

use common\classes\AutoBind\BindActionParamsTrait;
use common\models\Category;
use common\services\CategoryService;
use common\services\ProductService;

class CategoryController extends \yii\web\Controller
{
    use BindActionParamsTrait;

    /** @var ProductService */
    public $productService;

    /**
     * CategoryController constructor.
     * @param $id
     * @param $module
     * @param ProductService $productService
     * @param array $config
     */
    public function __construct($id, $module, ProductService $productService, $config = [])
    {
        $this->productService = $productService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param Category|null $category
     * @return string
     */
    public function actionIndex(Category $category = null)
    {
        return $this->render('index', [
            'model' => $category,
            'dataProvider' => $this->productService->search($category, \Yii::$app->request->get('limit', CategoryService::PAGE_LIMIT_DEFAULT))
        ]);
    }

}
