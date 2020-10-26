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
    /** @var string */
    public $category_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'sku', 'category_uuid', 'title', 'description', 'short', 'alias', 'meta_title', 'meta_description', 'meta_keywords', 'meta_robots', 'created_at', 'updated_at', 'category_title'], 'safe'],
            [['price'], 'number'],
            [['discount', 'viewed', 'purchased', 'rating'], 'integer'],
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
        $query = Product::find()->distinct();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'attributes' => [
                    'created_at' => [
                        'asc' => [
                            'created_at' => SORT_ASC
                        ],
                        'desc' => [
                            'created_at' => SORT_DESC
                        ],
                    ],
                    'updated_at' => [
                        'asc' => [
                            'updated_at' => SORT_ASC
                        ],
                        'desc' => [
                            'updated_at' => SORT_DESC
                        ],
                    ],
                    'title' => [
                        'asc' => [
                            'title' => SORT_ASC
                        ],
                        'desc' => [
                            'title' => SORT_DESC
                        ],
                    ],
                    'category_title' => [
                        'asc' => [
                            'category.title' => SORT_ASC
                        ],
                        'desc' => [
                            'category.title' => SORT_DESC
                        ],
                    ],
                    'sku',
                    'price',
                    'active',
                    'position',
                ],
                'defaultOrder' => [
                    'position' => SORT_ASC
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product.price' => $this->price,
            'product.discount' => $this->discount,
            'product.viewed' => $this->viewed,
            'product.purchased' => $this->purchased,
            'product.rating' => $this->rating,
            'product.position' => $this->position,
            'product.active' => $this->active,
            'product.created_at' => $this->created_at,
            'product.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'product.uuid', $this->uuid])
            ->andFilterWhere(['ilike', 'product.sku', $this->sku])
            ->andFilterWhere(['ilike', 'product.category_uuid', $this->category_uuid])
            ->andFilterWhere(['ilike', 'product.title', $this->title])
            ->andFilterWhere(['ilike', 'product.description', $this->description])
            ->andFilterWhere(['ilike', 'product.short', $this->short])
            ->andFilterWhere(['ilike', 'product.alias', $this->alias])
            ->andFilterWhere(['ilike', 'product.meta_title', $this->meta_title])
            ->andFilterWhere(['ilike', 'product.meta_description', $this->meta_description])
            ->andFilterWhere(['ilike', 'product.meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['ilike', 'product.meta_robots', $this->meta_robots])
            ->joinWith('category category', false)
            ->andFilterWhere(['ilike', 'category.title', $this->category_title]);

        return $dataProvider;
    }
}
