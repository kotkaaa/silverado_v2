<?php


namespace frontend\models;

use common\models\OrderInfo;

/**
 * Class OrderForm
 * @package frontend\models
 *
 * @property string|null $payment_type
 * @property string|null $delivery_type
 * @property string|null $user_name
 * @property string|null $user_phone
 * @property string|null $user_email
 * @property string|null $comment
 * @property string|null $location
 * @property string|null $address
 * @property string|null $recepient_name
 * @property string|null $recepient_phone
 */
class OrderForm extends \yii\base\Model
{
    /** @var string */
    public $payment_type;

    /** @var string */
    public $delivery_type;

    /** @var string */
    public $user_name;

    /** @var string */
    public $user_phone;

    /** @var string */
    public $user_email;

    /** @var string */
    public $comment;

    /** @var string */
    public $location;

    /** @var string */
    public $address;

    /** @var string */
    public $recepient_name;

    /** @var string */
    public $recepient_phone;

    /** @var string */
    public $address_1;

    /** @var string */
    public $address_2;

    /** @var bool */
    public $custom_recepient;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_name', 'user_email', 'user_phone', 'payment_type', 'delivery_type', 'location', 'address'], 'required'],
            [['recepient_name', 'recepient_phone'], 'required', 'when' => function (OrderForm $model) {
                return $this->custom_recepient;
            }, 'whenClient' => "function (attribute, value) {
                return $('#orderform-custom_recepient').is(':checked');
            }"],
            [['address_1'], 'required', 'when' => function (OrderForm $model) {
                return $model->delivery_type == OrderInfo::DELIVERY_METHOD_POST;
            }, 'whenClient' => "function (attribute, value) {
                return $('#orderform-delivery-type--0').is(':checked');
            }"],
            [['address_2'], 'required', 'when' => function (OrderForm $model) {
                return $model->delivery_type == OrderInfo::DELIVERY_METHOD_COURIER;
            }, 'whenClient' => "function (attribute, value) {
                return $('#orderform-delivery-type--1').is(':checked');
            }"],
            [['comment'], 'string'],
            [['custom_recepient'], 'boolean'],
            [['custom_recepient'], 'default', 'value' => false],
            [['payment_type', 'delivery_type', 'user_phone', 'recepient_phone'], 'string', 'max' => 32],
            [['user_name', 'location', 'address', 'address_1', 'address_2', 'recepient_name'], 'string', 'max' => 255],
            [['user_email'], 'string', 'max' => 64]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'user_name' => 'ФИО',
            'user_email' => 'E-mail',
            'user_phone' => 'Телефон',
            'payment_type' => 'Оплата',
            'delivery_type' => 'Доставка',
            'location' => 'Город',
            'address_1' => 'Почтовое отделение',
            'address_2' => 'Адрес доставки',
            'custom_recepient' => 'Получатель не я',
            'recepient_name' => 'ФИО получателя',
            'recepient_phone' => 'Телефон получателя',
            'comment' => 'Комментарий к заказу'
        ];
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        return [
            'payment_type' => 'Внимание! Оплата картой выполняется после подтверждения заказа. После оформления заказа, на указанный e-mail придет письмо со ссылкой на страницу оплаты',
            'custom_recepient' => 'Внимание! При получении заказа необходимо предьявить паспорт. Обязательно укажите имя и фамилию получателя'
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (!$this->custom_recepient) {
            $this->recepient_name = $this->user_name;
            $this->recepient_phone = $this->user_phone;
        }

        if ($this->delivery_type == OrderInfo::DELIVERY_METHOD_POST) {
            $this->address = $this->address_1;
        }

        if ($this->delivery_type == OrderInfo::DELIVERY_METHOD_COURIER) {
            $this->address = $this->address_2;
        }

        return parent::beforeValidate();
    }

    /**
     * @return OrderForm
     */
    public static function getInstance(): OrderForm
    {
        $form = new self();
        $form->delivery_type = OrderInfo::DELIVERY_METHOD_POST;
        $form->payment_type = OrderInfo::PAYMENT_METHOD_CASH;

        return $form;
    }
}