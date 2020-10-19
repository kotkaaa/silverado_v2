<?php


namespace backend\models;

use common\models\OrderInfo;

/**
 * Class OrderForm
 * @package backend\models
 */
class OrderForm extends \yii\base\Model
{
    /** @var string */
    public $status;

    /** @var string */
    public $source;

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
    public $note;

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
            [['source', 'status', 'user_name', 'user_phone', 'payment_type', 'delivery_type', 'location', 'address'], 'required'],
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
            [['comment', 'note'], 'string'],
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
            'user_name' => 'Name',
            'user_email' => 'E-mail',
            'user_phone' => 'Phone',
            'payment_type' => 'Payment type',
            'delivery_type' => 'Delivery type',
            'location' => 'City',
            'address_1' => 'Warehouse',
            'address_2' => 'Address',
            'custom_recepient' => 'Custom recepient',
            'recepient_name' => 'Recepient name',
            'recepient_phone' => 'Recepient phone',
            'comment' => 'Comment'
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