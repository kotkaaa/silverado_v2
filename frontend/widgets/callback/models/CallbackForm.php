<?php


namespace frontend\widgets\callback\models;

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
            [['phone'], 'match', 'pattern' => '/^(\+380)+(\d{9})$/'],
        ];
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