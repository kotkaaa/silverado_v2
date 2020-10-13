<?php

namespace common\queries;

use common\classes\Optional\OptionalActiveQueryTrait;
use common\models\Option;

/**
 * This is the ActiveQuery class for [[\common\models\OptionValue]].
 *
 * @see \common\models\OptionValue
 */
class OptionValueQuery extends \yii\db\ActiveQuery
{
    use OptionalActiveQueryTrait;

    /**
     * @param Option|null $option
     * @return OptionValueQuery
     */
    public function option(Option $option = null): OptionValueQuery
    {
        return $this->andFilterWhere([
            'option_value.option_uuid' => $option ? $option->uuid : null
        ]);
    }

    /**
     * @param string|array|null $action
     * @return OptionValueQuery
     */
    public function action($action = null): OptionValueQuery
    {
        return $this->andFilterWhere([
            'option_value.action' => $action
        ]);
    }

    /**
     * @return OptionValueQuery
     */
    public function ordered(): OptionValueQuery
    {
        return $this->orderBy([
            'option_value.position' => SORT_ASC
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\OptionValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
}
