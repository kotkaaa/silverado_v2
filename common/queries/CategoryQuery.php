<?php

namespace common\queries;

use common\classes\Optional\OptionalActiveQueryTrait;
use common\models\Category;

/**
 * This is the ActiveQuery class for [[\common\models\Category]].
 *
 * @see \common\models\Category
 */
class CategoryQuery extends \yii\db\ActiveQuery
{

    use OptionalActiveQueryTrait;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->alias('category');
    }

    /**
     * @return CategoryQuery
     */
    public function active(): CategoryQuery
    {
        return $this->andWhere([
            'category.active' => true
        ]);
    }

    /**
     * @param Category|null $category
     * @return CategoryQuery
     */
    public function parent(Category $category = null): CategoryQuery
    {
        return $this->andFilterWhere([
            'category.parent_uuid' => $category ? $category->uuid : null
        ]);
    }

    /**
     * @return CategoryQuery
     */
    public function ordered(): CategoryQuery
    {
        return $this->orderBy([
            'category.position' => SORT_ASC
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
}
