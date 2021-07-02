<?php

namespace common\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Review]].
 *
 * @see \common\models\Review
 */
class ReviewQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->alias('review');
    }

    /**
     * @return ReviewQuery
     */
    public function active(): ReviewQuery
    {
        return $this->andWhere([
            'review.deleted_at' => null
        ]);
    }

    /**
     * @return ReviewQuery
     */
    public function ordered(): ReviewQuery
    {
        return $this->orderBy([
            'review.created_at' => SORT_DESC
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Review[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Review|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
