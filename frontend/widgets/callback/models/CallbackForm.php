<?php


namespace frontend\widgets\callback\models;

use frontend\helpers\PhoneHelper;

/**
 * Class CallbackForm
 * @package frontend\widgets\callback\models
 */
class CallbackForm extends \yii\base\Model
{
    /** @var string */
    public $phone;

    /** @var string */
    public $name;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['phone', 'name'], 'safe'],
            [['phone', 'name'], 'trim'],
            [['phone', 'name'], 'required'],
            [['phone'], 'match', 'pattern' => '/^(\+38)+\s+(\d{3})+\s+(\d{3})+\s+(\d{2})+\s+(\d{2})$/'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $this->phone = PhoneHelper::format($this->phone, PhoneHelper::PHONE_FORMAT_FULL);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ім\'я',
            'phone' => 'Телефон'
        ];
    }
}