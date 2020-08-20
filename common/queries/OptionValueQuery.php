<?php

namespace common\queries;

use common\models\Option;

/**
 * This is the ActiveQuery class for [[\common\models\OptionValue]].
 *
 * @see \common\models\OptionValue
 */
class OptionValueQuery extends \yii\db\ActiveQuery
{
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
     * @param string|null $alias
     * @return OptionValueQuery
     */
    public function alias(string $alias = null): OptionValueQuery
    {
        return $this->andFilterWhere([
            'option_value.alias' => $alias
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
     * {@inheritdoc}
     * @return \common\models\OptionValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\OptionValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
