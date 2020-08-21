<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AttributeValue;

/**
 * AttributeValueSearch represents the model behind the search form of `common\models\AttributeValue`.
 */
class AttributeValueSearch extends AttributeValue
{
    /** @var string */
    public $attribute_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'attribute_uuid', 'title', 'alias', 'created_at', 'updated_at', 'attribute_title'], 'safe'],
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
        $query = AttributeValue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'uuid',
                    'title',
                    'alias',
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
                    'attribute_position' => [
                        'asc' => [
                            'attribute.position' => SORT_ASC
                        ],
                        'desc' => [
                            'attribute.position' => SORT_DESC
                        ]
                    ]
                ],
                'defaultOrder' => [
                    'attribute_position' => SORT_ASC,
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

        // grid filtering conditions
        $query->andFilterWhere([
            'attribute_value.position' => $this->position,
            'attribute_value.created_at' => $this->created_at,
            'attribute_value.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'attribute_value.uuid', $this->uuid])
            ->andFilterWhere(['ilike', 'attribute_value.attribute_uuid', $this->attribute_uuid])
            ->andFilterWhere(['ilike', 'attribute_value.title', $this->title])
            ->andFilterWhere(['ilike', 'attribute_value.alias', $this->alias])
            ->joinWith('attributeModel attribute', false)
            ->andFilterWhere(['ilike', 'attribute.title', $this->attribute_title]);

        return $dataProvider;
    }
}
