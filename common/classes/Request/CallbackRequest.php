<?php


namespace common\classes\Request;

/**
 * Class CallbackRequest
 * @package common\classes\Request
 */
class CallbackRequest extends BaseRequest
{
    /** @var string|null */
    public $name = 'Anonymous';

    /** @var string|null */
    public $phone;

    /** @var string */
    protected $type = 'callback';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $time = time();
        $now = (int) date('G', $time);

        if ($now >= 20) {
            $this->lifetime += mktime(9, 0, 0, date('m'), date('d') + 1, date('Y')) - $time;
        } elseif ($now < 9) {
            $this->lifetime += mktime(9, 0, 0, date('m'), date('d'), date('Y')) - $time;
        }
    }

    /**
     * @return bool
     */
    public function send(): bool
    {
        return \Yii::$app->sms->compose($this->type, ['request' => $this])
            ->setTo(\Yii::$app->params['supportPhone'])
            ->send();
    }
}