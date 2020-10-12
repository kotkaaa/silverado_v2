<?php


namespace common\strategies;


use common\models\FilterValue;
use common\models\Product;

class OptionFilterStrategy extends FilterStrategy implements FilterStrategyInterface
{
    /**
     * @return void
     */
    public function initValues()
    {
        foreach ($this->filter->optionModel->values as $optionValue)
        {
            $matches = Product::find()
                ->category($this->category)
                ->innerJoinWith('optionValues values')
                ->andFilterWhere(['values.alias' => $optionValue->alias])
                ->exists();

            if (!$matches) {
                continue;
            }

            /** @var FilterValue $value */
            $value = \Yii::createObject([
                'class' => FilterValue::class,
                'label' => $optionValue->title,
                'alias' => $optionValue->alias,
                'matches' => $matches,
                'checked' => in_array($optionValue->alias, $this->selectedFilters)
            ]);

            $value->count = $value->checked ? (clone $this->queryBuilder)->removeFilter($value->alias)->count()
                : (clone $this->queryBuilder)->appendFilter($value->alias)->count();

            $value->url = $value->checked ? (clone $this->urlBuilder)->removeAttribute($value->alias)->buildUrl()
                : (clone $this->urlBuilder)->appendAttribute($value->alias)->buildUrl();

            $this->count++;

            $this->values[] = $value;
        }
    }
}