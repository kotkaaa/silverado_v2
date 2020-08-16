<?php

namespace common\queries;

use common\classes\Optional\OptionalActiveQueryTrait;
use common\models\Category;

/**
 * This is the ActiveQuery class for [[\common\models\Product]].
 *
 * @see \common\models\Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{

    use OptionalActiveQueryTrait;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->alias('product');
    }

    /**
     * @return ProductQuery
     */
    public function active(): ProductQuery
    {
        return $this->andWhere([
            'product.active' => true
        ]);
    }

    /**
     * @return ProductQuery
     */
    public function inactive(): ProductQuery
    {
        return $this->andWhere([
            'product.active' => false
        ]);
    }

    /**
     * @param Category|null $category
     * @return ProductQuery
     */
    public function category(Category $category = null): ProductQuery
    {
        return $this->andFilterWhere([
            'product.category_uuid' => $category ? $category->uuid : null
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
}
