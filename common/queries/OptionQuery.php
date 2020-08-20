<?php

namespace common\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Option]].
 *
 * @see \common\models\Option
 */
class OptionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->alias('option');
    }

    /**
     * @return OptionQuery
     */
    public function ordered(): OptionQuery
    {
        return $this->orderBy([
            'option.position' => SORT_ASC
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Option[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Option|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
