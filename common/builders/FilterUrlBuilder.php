<?php


namespace common\builders;


use common\models\Category;

/**
 * Class FilterUrlBuilder
 * @package common\builders
 */
class FilterUrlBuilder extends \yii\base\BaseObject
{
    /** @var Category */
    public $category;

    /** @var array */
    public $attributes = [];

    /** @var string */
    private $route = '/category';

    /** @var string */
    private $key = 'q';

    /**
     * @param mixed $attribute
     * @return FilterUrlBuilder
     */
    public function appendAttribute($attribute): FilterUrlBuilder
    {
        if (!in_array($attribute, $this->attributes)) {
            $this->attributes[] = $attribute;
        }

        return $this;
    }

    /**
     * @param mixed $attribute
     * @return FilterUrlBuilder
     */
    public function removeAttribute($attribute): FilterUrlBuilder
    {
        if (($key = array_search($attribute, $this->attributes)) !== false) {
            unset($this->attributes[$key]);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function buildUrl(): string
    {
        $params = [$this->route];

        if ($this->category) {
            $params[] = $this->category->alias;
        }

        if (count($this->attributes)) {
            $params[] = $this->key;
            $params[] = implode(',', $this->attributes);
        }

        return implode('/', $params);
    }
}