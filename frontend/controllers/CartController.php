<?php


namespace frontend\controllers;

use common\classes\AutoBind\BindActionParamsTrait;
use common\models\Order;
use common\models\Product;
use common\services\CartService;
use common\services\PurchaseService;
use frontend\models\OrderForm;

/**
 * Class CartController
 * @package frontend\controllers
 */
class CartController extends \yii\web\Controller
{
    use BindActionParamsTrait;

    /** @var CartService */
    public $cartService;

    /** @var PurchaseService */
    public $purchaseService;

    /**
     * CartController constructor.
     * @param $id
     * @param $module
     * @param CartService $cartService
     * @param PurchaseService $purchaseService
     * @param array $config
     */
    public function __construct($id, $module, CartService $cartService, PurchaseService $purchaseService, $config = [])
    {
        $this->cartService = $cartService;
        $this->purchaseService = $purchaseService;
        $this->layout = 'cart';
        parent::__construct($id, $module, $config);
    }

    /**
     * @return string|void
     */
    public function actionIndex()
    {
        if (!\Yii::$app->cart->getCount()) {
            \Yii::$app->session->setFlash('error', 'Корзина пуста!');
            return $this->redirect('/')->send();
        }

        $form = OrderForm::getInstance();

        if ($form->load(\Yii::$app->request->post()) && ($order = $this->purchaseService->purchase($form)) !== null) {
            \Yii::$app->session->setFlash('success', 'Заказ оформлен!');
            return $this->redirect(['/cart/success/' . $order->id])->send();
        }

        return $this->render('index', [
            'orderForm' => $form
        ]);
    }

    /**
     * @param Order $order
     * @return string
     */
    public function actionSuccess(Order $order)
    {
        if (!\Yii::$app->cart->getCount()) {
            return $this->redirect('/')->send();
        }

        $this->cartService->clear();

        return $this->render('success', [
            'model' => $order
        ]);
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

    /**
     * @param $term
     * @return \yii\web\Response
     */
    public function actionSearchCity($term)
    {
        $out = ['results' => []];

        foreach ($this->purchaseService->findCity($term) as $city) {
            $out['results'][] = [
                'id' => $city['DescriptionRu'],
                'text' => $city['DescriptionRu'],
                'ref' => $city['Ref']
            ];
        }

        return $this->asJson($out);
    }

    /**
     * @param $term
     * @return \yii\web\Response
     */
    public function actionSearchWarehouse($term)
    {
        $out = ['results' => []];

        foreach ($this->purchaseService->findWarehouse($term) as $warehouse) {
            $out['results'][] = [
                'id' => $warehouse['DescriptionRu'],
                'text' => $warehouse['DescriptionRu'],
                'ref' => $warehouse['Ref']
            ];
        }

        return $this->asJson($out);
    }
}