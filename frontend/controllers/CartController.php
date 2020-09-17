<?php


namespace frontend\controllers;

use common\classes\AutoBind\BindActionParamsTrait;
use common\models\Product;
use common\services\CartService;

/**
 * Class CartController
 * @package frontend\controllers
 */
class CartController extends \yii\web\Controller
{
    use BindActionParamsTrait;

    /** @var CartService */
    public $cartService;

    /**
     * CartController constructor.
     * @param $id
     * @param $module
     * @param CartService $cartService
     * @param array $config
     */
    public function __construct($id, $module, CartService $cartService, $config = [])
    {
        $this->cartService = $cartService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param Product $product
     * @return void
     */
    public function actionAdd(Product $product)
    {
        $product->load(\Yii::$app->request->post());
        $product->scenario = Product::SCENARIO_PURCHASE;

        try {
            $this->cartService->add($product);
            \Yii::$app->session->setFlash('success', 'Товар добавлен в корзину');
        } catch (\Exception $exception) {
            \Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(\Yii::$app->request->referrer)->send();
    }

    /**
     * @param string $id
     * @return void
     */
    public function actionRemove($id)
    {
        try {
            $this->cartService->remove($id);
            \Yii::$app->session->setFlash('success', 'Товар удален из корзины');
        } catch (\Exception $exception) {
            \Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(\Yii::$app->request->referrer)->send();
    }

    /**
     * @return void
     */
    public function actionClear()
    {
        try {
            $this->cartService->clear();
            \Yii::$app->session->setFlash('success', 'Корзина пуста');
        } catch (\Exception $exception) {
            \Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(\Yii::$app->request->referrer)->send();
    }
}