<?php

namespace common\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Filter]].
 *
 * @see \common\models\Filter
 */
class FilterQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->alias('filter');
    }

    /**
     * @return FilterQuery
     */
    public function ordered(): FilterQuery
    {
        return $this->orderBy(['filter.position' => SORT_ASC]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Filter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Filter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
