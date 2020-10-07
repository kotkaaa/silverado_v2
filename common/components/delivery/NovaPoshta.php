<?php


namespace common\components\delivery;


use common\components\delivery\api\NovaPoshtaApi;
use yii\base\InvalidConfigException;

/**
 * Class NovaPoshta
 * @package common\components\delivery
 */
class NovaPoshta extends \yii\base\Component
{
    /** @var NovaPoshtaApi */
    private $api;

    /** @var string */
    public $key;

    /** @var string */
    public $language = 'ru';

    /** @var bool */
    public $throwErrors = false;

    /** @var string */
    public $connectionType = 'json';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->key) {
            throw new InvalidConfigException('A valid API key must be set.');
        }

        $this->api = new NovaPoshtaApi(
            $this->key,
            $this->language,
            $this->throwErrors,
            $this->connectionType
        );

        parent::init();
    }

    /**
     * @return NovaPoshtaApi
     */
    public function api()
    {
        return $this->api;
    }
}