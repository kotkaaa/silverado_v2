<?php


namespace common\services;

use common\components\delivery\api\NovaPoshtaApi;
use common\models\Order;
use common\models\OrderInfo;
use common\models\OrderProduct;
use common\models\Product;
use yii\base\Model;

/**
 * Class PurchaseService
 * @package common\services
 */
class PurchaseService extends \yii\base\Component
{
    /** @var NovaPoshtaApi */
    private $delivery;

    /**
     * PurchaseService constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->delivery = \Yii::$app->delivery->api();
        parent::__construct($config);
    }

    /**
     * @param Model $orderForm
     * @return Order|null
     */
    public function purchase(Model $orderForm): ?Order
    {
        if (!$orderForm->validate()) {
            return null;
        }

        $orderInfo = new OrderInfo();
        $orderInfo->setAttributes($orderForm->getAttributes());

        if ($orderInfo->validate() && (($order = new Order()) !== null && $order->save())) {

            $order->link('orderInfo', $orderInfo);

            foreach (\Yii::$app->cart->getItems() as $product) {
                /** @var Product $product */
                $orderProduct = new OrderProduct([
                    'product_uuid' => $product->uuid
                ]);
                $orderProduct->setAttributes($product->getAttributes(null, ['uuid']));
                $orderProduct->options = $product->selectedOptions;

                $order->link('orderProducts', $orderProduct);
            }

            return $order;
        }

        return null;
    }

    /**
     * @param string $term
     * @return array|mixed
     */
    public function findCity($term = '')
    {
        $data = [];

        $response = $this->delivery->getCities(0, $term);

        if (is_array($response) && $response['success'] === true) {
            $data = $response['data'];
        }

        return $data;
    }

    /**
     * @param string $term
     * @return array|mixed
     */
    public function findWarehouse($term = '')
    {
        $data = [];

        $response = $this->delivery->getWarehouse($term);

        if (is_array($response) && $response['success'] === true) {
            $data = $response['data'];
        }

        return $data;
    }
}