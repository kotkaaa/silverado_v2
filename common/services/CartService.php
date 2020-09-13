<?php


namespace common\services;

use common\exceptions\CartHttpException;
use yii2mod\cart\models\CartItemInterface;

/**
 * Class CartService
 * @package common\services
 */
class CartService
{
    /** @var \common\components\Cart */
    private $cart;

    /**
     * CartService constructor.
     */
    public function __construct()
    {
        $this->cart = \Yii::$app->cart;
    }

    /**
     * @param CartItemInterface $product
     * @return bool
     * @throws CartHttpException
     */
    public function add(CartItemInterface $product)
    {
        try {
            $this->cart->add($product);
        } catch (\Exception $exception) {
            throw new CartHttpException($exception->getMessage());
        }

        return true;
    }

    /**
     * @param CartItemInterface $product
     * @return bool
     * @throws CartHttpException
     */
    public function remove(CartItemInterface $product)
    {
        try {
            $this->cart->remove($product->getUniqueId());
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