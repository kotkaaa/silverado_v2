<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Category;

/**
 * CategorySearch represents the model behind the search form of `common\models\Category`.
 */
class CategorySearch extends Category
{
    /** @var string */
    public $parent_title;

    /** @var bool */
    public $has_children;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'parent_uuid', 'title', 'description', 'alias', 'icon', 'image', 'meta_title', 'meta_description', 'meta_keywords', 'meta_robots', 'created_at', 'updated_at', 'parent_title'], 'safe'],
//            [['position'], 'integer'],
            [['active', 'has_children'], 'boolean'],
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
        $query = Category::find();

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
            'category.created_at' => $this->created_at,
            'category.updated_at' => $this->updated_at,
            'category.position' => $this->position,
            'category.active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'category.uuid', $this->uuid])
            ->andFilterWhere(['like', 'category.parent_uuid', $this->parent_uuid])
            ->andFilterWhere(['like', 'category.title', $this->title])
            ->andFilterWhere(['like', 'category.description', $this->description])
            ->andFilterWhere(['like', 'category.alias', $this->alias])
            ->andFilterWhere(['like', 'category.icon', $this->icon])
            ->andFilterWhere(['like', 'category.image', $this->image])
            ->andFilterWhere(['like', 'category.meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'category.meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'category.meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'category.meta_robots', $this->meta_robots])
            ->joinWith('parent parent', false)
            ->andFilterWhere(['like', 'parent.title', $this->parent_title]);

        if ($this->has_children) {
            $query->innerJoinWith('children children', false)
                ->groupBy('category.uuid');
        }

        if (!$this->parent_uuid) {
            $query->root();
        }

        return $dataProvider;
    }
}
