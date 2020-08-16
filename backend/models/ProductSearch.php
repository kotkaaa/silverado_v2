<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'sku', 'category_uuid', 'title', 'description', 'short', 'alias', 'meta_title', 'meta_description', 'meta_keywords', 'meta_robots', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
            [['discount', 'viewed', 'purchased', 'rating', 'position'], 'integer'],
            [['active'], 'boolean'],
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
        $query = Product::find();

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
            'price' => $this->price,
            'discount' => $this->discount,
            'viewed' => $this->viewed,
            'purchased' => $this->purchased,
            'rating' => $this->rating,
            'position' => $this->position,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'uuid', $this->uuid])
            ->andFilterWhere(['ilike', 'sku', $this->sku])
            ->andFilterWhere(['ilike', 'category_uuid', $this->category_uuid])
            ->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'short', $this->short])
            ->andFilterWhere(['ilike', 'alias', $this->alias])
            ->andFilterWhere(['ilike', 'meta_title', $this->meta_title])
            ->andFilterWhere(['ilike', 'meta_description', $this->meta_description])
            ->andFilterWhere(['ilike', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['ilike', 'meta_robots', $this->meta_robots]);

        return $dataProvider;
    }
}
