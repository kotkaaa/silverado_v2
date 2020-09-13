<?php


namespace common\components;

use yii2mod\cart\models\CartItemInterface;

/**
 * Class Cart
 * @package common\components
 */
class Cart extends \yii2mod\cart\Cart
{
    /**
     * @param CartItemInterface $element
     * @param bool $save
     * @return \yii2mod\cart\Cart
     */
    public function add(CartItemInterface $element, $save = true): \yii2mod\cart\Cart
    {
        if ($this->exists($element)) {
            $element->quantity += 1;
        }

        $element->price = $element->price * $element->quantity;

        return parent::add($element, $save);
    }

    /**
     * @param string|int $id
     * @return bool
     */
    public function exists($id): bool
    {
        return array_key_exists($id, $this->items);
    }

    /**
     * @param string|int $id
     * @return CartItemInterface|null
     */
    public function getItem($id): ?CartItemInterface
    {
        return $this->exists($id) ? $this->items[$id] : null;
    }

    /**
     * @param string|int $id
     * @return int
     */
    public function getQuantity($id): int
    {
        if (($element = $this->getItem($id)) == null) {
            return 0;
        }

        return $element->quantity;
    }


    public function setQuantity($id, int $quantity): void
    {
        if (($element = $this->getItem($id)) == null) {
            return;
        }
    }
}