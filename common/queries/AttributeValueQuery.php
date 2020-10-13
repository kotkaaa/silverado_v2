<?php

namespace common\queries;

use common\classes\Optional\OptionalActiveQueryTrait;

/**
 * This is the ActiveQuery class for [[\common\models\AttributeValue]].
 *
 * @see \common\models\AttributeValue
 */
class AttributeValueQuery extends \yii\db\ActiveQuery
{
    use OptionalActiveQueryTrait;

    /**
     * {@inheritdoc}
     * @return \common\models\AttributeValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
}
