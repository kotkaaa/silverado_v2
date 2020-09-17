<?php


namespace common\services;

use common\exceptions\CartHttpException;
use common\models\Order;

/**
 * Class PurchaseService
 * @package common\services
 */
class PurchaseService extends \yii\base\Component
{
    /** @var string */
    public const PAYMENT_METHOD_CASH = 'cash';
    public const PAYMENT_METHOD_CARD = 'card';

    /** @var string */
    public const DELIVERY_METHOD_POST = 'post';
    public const DELIVERY_METHOD_COURIER = 'courier';

    /** @var CartService */
    public $cartService;

    /**
     * PurchaseService constructor.
     * @param CartService $cartService
     * @param array $config
     */
    public function __construct(CartService $cartService, $config = [])
    {
        $this->cartService = $cartService;
        parent::__construct($config);
    }


    public function purchase()
    {
        if (!$this->cartService->cart->getCount()) {
            throw new CartHttpException(404, 'Корзина пуста!');
        }

        $order = new Order();

    }

    /**
     * @param bool $onlyKeys
     * @return array
     */
    public static function paymentList($onlyKeys = false): array
    {
        $list = [
            self::PAYMENT_METHOD_CASH => 'Наличными',
            self::PAYMENT_METHOD_CARD => 'Visa/MasterCard'
        ];

        if ($onlyKeys) {
            return array_keys($list);
        }

        return $list;
    }

    /**
     * @param bool $onlyKeys
     * @return array
     */
    public static function deliveryList($onlyKeys = false): array
    {
        $list = [
            self::DELIVERY_METHOD_POST => 'Почтовое отделение (Нова Пошта)',
            self::DELIVERY_METHOD_COURIER => 'Курьером по адресу (Нова Пошта)'
        ];

        if ($onlyKeys) {
            return array_keys($list);
        }

        return $list;
    }
}