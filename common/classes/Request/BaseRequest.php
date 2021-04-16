<?php


namespace common\classes\Request;

use yii\base\InvalidConfigException;
use yii\web\Cookie;

/**
 * Class BaseRequest
 * @package common\classes\Request
 */
class BaseRequest extends \yii\base\BaseObject implements RequestInterface
{
    /** @var string */
    public const STORAGE_CACHE = 'cache';
    public const STORAGE_COOKIE = 'cookie';

    /** @var string */
    public $id;

    /** @var string */
    public $storage;

    /** @var string */
    protected $type = 'base';

    /** @var int */
    public $lifetime = 60;

    /** @var int|null */
    public $expires_at;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->id = \Yii::$app->security->hashData($this->type . \Yii::$app->session->id, $this->type);

        if (!$this->storage) {
            throw new InvalidConfigException('Request storage not set.');
        }
    }

    /**
     * @return RequestInterface
     */
    public function push(): RequestInterface
    {
        $this->expires_at = time() + $this->lifetime;

        switch ($this->storage) {
            // Save request in cache
            case self::STORAGE_CACHE:
                \Yii::$app->cache->set($this->id, serialize($this), $this->lifetime);
                break;
            // Save request in cookie
            case self::STORAGE_COOKIE:
                \Yii::$app->response->cookies->add(new Cookie([
                    'name' => $this->id,
                    'value' => serialize($this),
                    'httpOnly' => false,
                    'expire' => $this->expires_at
                ]));
                break;
        }

        return $this;
    }

    /**
     * @return RequestInterface|null
     */
    public function pull(): ?RequestInterface
    {
        $value = null;

        switch ($this->storage) {
            // Get request from cache
            case self::STORAGE_CACHE:
                $value = \Yii::$app->cache->get($this->id);
                break;
            // Get request from cookie
            case self::STORAGE_COOKIE:
                $value = \Yii::$app->request->cookies->getValue($this->id);
                break;
        }

        return $value ? unserialize($value, ['allowed_classes' => true]) : $value;
    }
}