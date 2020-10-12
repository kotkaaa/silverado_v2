<?php


namespace common\models;

/**
 * Class FilterValue
 * @package common\models
 */
class FilterValue extends \yii\base\Model
{
    /** @var string */
    public $label;

    /** @var string */
    public $alias;

    /** @var string */
    public $url;

    /** @var int */
    public $matches;

    /** @var int */
    public $count;

    /** @var bool */
    public $checked;
}