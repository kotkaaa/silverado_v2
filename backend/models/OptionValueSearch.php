<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OptionValue;

/**
 * OptionValueSearch represents the model behind the search form of `common\models\OptionValue`.
 */
class OptionValueSearch extends OptionValue
{
    /** @var string */
    public $option_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'option_uuid', 'title', 'alias', 'action', 'created_at', 'updated_at', 'option_title'], 'safe'],
            [['price'], 'number'],
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
        $query = OptionValue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'option_value.price' => $this->price,
            'option_value.position' => $this->position,
            'option_value.created_at' => $this->created_at,
            'option_value.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'option_value.uuid', $this->uuid])
            ->andFilterWhere(['ilike', 'option_value.option_uuid', $this->option_uuid])
            ->andFilterWhere(['ilike', 'option_value.title', $this->title])
            ->andFilterWhere(['ilike', 'option_value.alias', $this->alias])
            ->andFilterWhere(['ilike', 'option_value.action', $this->action])
            ->joinWith('option', false)
            ->andFilterWhere(['ilike', 'option.title', $this->option_title]);

        return $dataProvider;
    }
}
