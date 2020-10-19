<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Filter;

/**
 * FilterSearch represents the model behind the search form of `common\models\Filter`.
 */
class FilterSearch extends Filter
{
    /** @var string */
    public $attribute_title;

    /** @var string */
    public $option_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'attribute_uuid', 'option_uuid', 'title', 'strategy_class', 'created_at', 'updated_at', 'attribute_title', 'option_title'], 'safe'],
            [['position'], 'integer'],
        ];
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
        $query = Filter::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'uuid',
                    'title',
                    'alias',
                    'strategy_class',
                    'position',
                    'created_at',
                    'updated_at',
                    'attribute_title' => [
                        'asc' => [
                            'attribute.title' => SORT_ASC
                        ],
                        'desc' => [
                            'attribute.title' => SORT_DESC
                        ]
                    ],
                    'option_title' => [
                        'asc' => [
                            'option.title' => SORT_ASC
                        ],
                        'desc' => [
                            'option.title' => SORT_DESC
                        ]
                    ]
                ],
                'defaultOrder' => [
                    'position' => SORT_ASC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'filter.position' => $this->position,
            'filter.created_at' => $this->created_at,
            'filter.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'filter.uuid', $this->uuid])
            ->andFilterWhere(['ilike', 'filter.attribute_uuid', $this->attribute_uuid])
            ->andFilterWhere(['ilike', 'filter.option_uuid', $this->option_uuid])
            ->andFilterWhere(['ilike', 'filter.title', $this->title])
            ->andFilterWhere(['ilike', 'filter.strategy_class', $this->strategy_class])
            ->joinWith('attributeModel attribute', false)
            ->andFilterWhere(['ilike', 'attribute.title', $this->attribute_title])
            ->joinWith('optionModel option', false)
            ->andFilterWhere(['ilike', 'option.title', $this->option_title]);

        return $dataProvider;
    }
}
