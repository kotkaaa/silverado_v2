<?php

namespace common\queries;

/**
 * This is the ActiveQuery class for [[\common\models\ReviewFiles]].
 *
 * @see \common\models\ReviewFiles
 */
class ReviewFilesQuery extends \yii\db\ActiveQuery
{
    /**
     * @return ReviewFilesQuery
     */
    public function ordered(): ReviewFilesQuery
    {
        return $this->orderBy([
            'created_at' => SORT_ASC
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ReviewFiles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ReviewFiles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
