<?php

namespace common\queries;

use common\models\Option;
use common\models\OptionValue;
use common\models\Product;

/**
 * This is the ActiveQuery class for [[\common\models\ProductOption]].
 *
 * @see \common\models\ProductOption
 */
class ProductOptionQuery extends \yii\db\ActiveQuery
{
    /**
     * @param Product $product
     * @return ProductOptionQuery
     */
    public function product(Product $product): ProductOptionQuery
    {
        return $this->andWhere([
            'product_option.product_uuid' => $product->uuid
        ]);
    }

    /**
     * @param OptionValue $value
     * @return ProductOptionQuery
     */
    public function value(OptionValue $value): ProductOptionQuery
    {
        return $this->andWhere([
            'product_option.value_uuid' => $value->uuid
        ]);
    }

    /**
     * @param Option $option
     * @return ProductOptionQuery
     */
    public function option(Option $option): ProductOptionQuery
    {
        return $this->innerJoinWith('value value', false)
            ->andWhere(['value.option_uuid' => $option->uuid]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductOption[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductOption|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
