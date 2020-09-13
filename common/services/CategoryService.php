<?php


namespace common\services;

use common\models\Category;
use common\queries\CategoryQuery;
use yii\db\ActiveQueryInterface;

/**
 * Class CategoryService
 * @package common\services
 */
class CategoryService
{
    /** @var string */
    public const SORT_ORDER_DEFAULT = 'position';
    public const SORT_ORDER_PRICE_ASC = 'price';
    public const SORT_ORDER_PRICE_DESC = '-price';

    /** @var int */
    public const PAGE_LIMIT_DEFAULT = 12;
    public const PAGE_LIMIT_X2 = 24;
    public const PAGE_LIMIT_X4 = 48;
    public const PAGE_LIMIT_ALL = -1;

    /**
     * @return CategoryQuery
     */
    public function find(): ActiveQueryInterface
    {
        return Category::find()->active();
    }

    /**
     * @param bool $onlyKeys
     * @return array
     */
    public static function sortOrderList($onlyKeys = false): array
    {
        $list = [
            self::SORT_ORDER_DEFAULT => 'По умолчанию',
            self::SORT_ORDER_PRICE_ASC => 'Цена (по возрастанию)',
            self::SORT_ORDER_PRICE_DESC => 'Цена (по убыванию)'
        ];

        if ($onlyKeys) {
            return array_keys($list);
        }

        return $list;
    }
}