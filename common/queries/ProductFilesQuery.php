<?php

namespace common\queries;

use common\models\Product;

/**
 * This is the ActiveQuery class for [[\common\models\ProductFiles]].
 *
 * @see \common\models\ProductFiles
 */
class ProductFilesQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->alias('product_files');
    }

    /**
     * @return ProductFilesQuery
     */
    public function product(Product $product): ProductFilesQuery
    {
        return $this->andWhere([
            'product_files.product_uuid' => $product->uuid
        ]);
    }

    /**
     * @return ProductFilesQuery
     */
    public function ordered(): ProductFilesQuery
    {
        return $this->orderBy([
            'product_files.position' => SORT_ASC
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductFiles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductFiles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
