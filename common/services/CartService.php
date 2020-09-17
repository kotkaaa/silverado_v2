<?php


namespace common\services;

use common\exceptions\CartHttpException;
use common\models\Product;
use yii2mod\cart\models\CartItemInterface;

/**
 * Class CartService
 * @package common\services
 */
class CartService
{
    /** @var \common\components\Cart */
    public $cart;

    /**
     * CartService constructor.
     */
    public function __construct()
    {
        $this->cart = \Yii::$app->cart;
    }

    /**
     * @param Product $product
     * @return bool
     * @throws CartHttpException
     */
    public function add(CartItemInterface $product)
    {
        if (!$product->validate()) {
            return false;
        }

        try {
            $this->cart->add($product);
        } catch (\Exception $exception) {
            throw new CartHttpException(500, $exception->getMessage());
        }

        return true;
    }

    /**
     * @param string $id
     * @return bool
     * @throws CartHttpException
     */
    public function remove(string $id)
    {
        try {
            $this->cart->remove($id);
        } catch (\Exception $exception) {
            throw new CartHttpException($exception->getMessage());
        }

        return true;
    }

    /**
     * @return bool
     * @throws CartHttpException
     */
    public function clear()
    {
        try {
            $this->cart->clear();
        } catch (\Exception $exception) {
            throw new CartHttpException($exception->getMessage());
        }

        return true;
    }
}