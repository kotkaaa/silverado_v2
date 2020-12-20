<?php

namespace common\queries;

use common\models\Product;

/**
 * This is the ActiveQuery class for [[\common\models\ProductSet]].
 *
 * @see \common\models\ProductSet
 */
class ProductSetQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->alias('set');
    }

    /**
     * @param Product $product
     * @return ProductSetQuery
     */
    public function master(Product $product)
    {
        return $this->andWhere(['set.master_uuid' => $product->uuid]);
    }

    /**
     * @param Product $product
     * @return ProductSetQuery
     */
    public function slave(Product $product)
    {
        return $this->andWhere(['set.slave_uuid' => $product->uuid]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductSet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductSet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
