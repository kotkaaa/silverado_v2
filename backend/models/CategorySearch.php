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
            [['position'], 'integer'],
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

        $query->andFilterWhere(['ilike', 'category.uuid', $this->uuid])
            ->andFilterWhere(['ilike', 'category.parent_uuid', $this->parent_uuid])
            ->andFilterWhere(['ilike', 'category.title', $this->title])
            ->andFilterWhere(['ilike', 'category.description', $this->description])
            ->andFilterWhere(['ilike', 'category.alias', $this->alias])
            ->andFilterWhere(['ilike', 'category.icon', $this->icon])
            ->andFilterWhere(['ilike', 'category.image', $this->image])
            ->andFilterWhere(['ilike', 'category.meta_title', $this->meta_title])
            ->andFilterWhere(['ilike', 'category.meta_description', $this->meta_description])
            ->andFilterWhere(['ilike', 'category.meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['ilike', 'category.meta_robots', $this->meta_robots])
            ->joinWith('parent parent', false)
            ->andFilterWhere(['ilike', 'parent.title', $this->parent_title]);

        if ($this->has_children) {
            $query->innerJoinWith('children children', false);
        }

        if (!$this->parent_uuid) {
            $query->root();
        }

        return $dataProvider;
    }
}
