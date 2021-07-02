<?php

namespace frontend\widgets\product\set;

use common\models\Product;
use yii\base\InvalidConfigException;

/**
 * Class ProductSet
 * @package frontend\widgets\product\set
 */
class ProductSet extends \yii\base\Widget
{
    /** @var Product */
    public $product;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->product) {
            throw new InvalidConfigException('Product property not set.');
        }

        parent::init();
    }

    /**
     * @return string|null
     */
    public function run()
    {
        if ($this->product->_set) {
            return $this->render('master', [
                'master' => $this->product,
                'slaves' => $this->product->set
            ]);
        }

        if ($this->product->master) {
            return $this->render('slave', [
                'master' => $this->product->master,
                'slaves' => $this->product->master->set
            ]);
        }

        return null;
    }
}