<?php

namespace frontend\controllers;

use common\classes\AutoBind\BindActionParamsTrait;
use common\models\Category;
use common\services\FilterService;
use common\services\ProductService;
use common\builders\FilterQueryBuilder;

/**
 * Class CategoryController
 * @package frontend\controllers
 */
class CategoryController extends \yii\web\Controller
{
    use BindActionParamsTrait;

    /** @var ProductService */
    public $productService;

    /** @var FilterService */
    public $filterService;

    /**
     * CategoryController constructor.
     * @param $id
     * @param $module
     * @param ProductService $productService
     * @param array $config
     */
    public function __construct($id, $module, ProductService $productService, FilterService $filterService, $config = [])
    {
        $this->productService = $productService;
        $this->filterService = $filterService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param Category|null $category
     * @return string
     */
    public function actionIndex(Category $category = null, $query = null, $page = 1)
    {
        /** @var FilterQueryBuilder $searchModel */
        $searchModel = \Yii::createObject([
            'class' => FilterQueryBuilder::class,
            'category' => $category,
            'filters' => $this->filterService->parseQuery($query)
        ]);

        $categoryFilters = $this->filterService->getCategoryFilters($category, $searchModel);

        return $this->render('index', [
            'model' => $category,
            'filters' => $categoryFilters,
            'dataProvider' => $searchModel->search()
        ]);
    }

}
