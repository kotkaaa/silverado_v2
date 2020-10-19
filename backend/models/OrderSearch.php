<?php

namespace backend\models;

use kartik\daterange\DateRangeBehavior;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /** @var string */
    public $created_from;
    public $created_to;
    public $created_range;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_range'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            [['created_at', 'updated_at', 'deleted_at', 'status', 'source'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'created_range',
                'dateStartAttribute' => 'created_from',
                'dateEndAttribute' => 'created_to',
                'dateFormat' => 'Y-m-d'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order.id' => $this->id,
            'order.created_at' => $this->created_at,
            'order.updated_at' => $this->updated_at
        ]);

        $query->andFilterWhere(['ilike', 'order.status', $this->status])
            ->andFilterWhere(['ilike', 'order.source', $this->source]);

        if ($this->created_range) {
            $query->andFilterWhere(['>=', 'date("order".created_at)', date('Y-m-d', $this->created_from)])
                ->andFilterWhere(['<=', 'date("order".created_at)', date('Y-m-d', $this->created_to)]);
        }

        return $dataProvider;
    }
}
