<?php

namespace common\queries;

use common\models\Order;

/**
 * This is the ActiveQuery class for [[Order]].
 *
 * @see Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->andWhere(['order.deleted_at' => null]);
    }

    /**
     * @param string|array|null $status
     * @return OrderQuery
     */
    public function status($status = null): OrderQuery
    {
        return $this->andFilterWhere(['order.status' => $status]);
    }

    /**
     * {@inheritdoc}
     * @return Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
