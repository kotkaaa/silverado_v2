<?php


namespace common\strategies;

use common\models\FilterValue;
use common\models\Product;

/**
 * Class FilterAttributeStrategy
 * @package common\strategies
 */
class AttributeFilterStrategy extends FilterStrategy implements FilterStrategyInterface
{
    /**
     * @return void
     */
    public function initValues()
    {
        foreach ($this->filter->attributeModel->values as $attributeValue)
        {
            $matches = Product::find()
                ->category($this->category)
                ->innerJoinWith('attributeValues values')
                ->andFilterWhere(['values.alias' => $attributeValue->alias])
                ->exists();

            if (!$matches) {
                continue;
            }

            /** @var FilterValue $value */
            $value = \Yii::createObject([
                'class' => FilterValue::class,
                'label' => $attributeValue->title,
                'alias' => $attributeValue->alias,
                'matches' => $matches,
                'checked' => in_array($attributeValue->alias, $this->selectedFilters)
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