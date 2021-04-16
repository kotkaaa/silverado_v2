<?php


namespace common\classes\Request;

/**
 * Interface RequestInterface
 * @package common\classes\Request
 */
interface RequestInterface extends \yii\base\Configurable
{
    /** @return RequestInterface */
    public function push(): RequestInterface;

    /** @return RequestInterface|null */
    public function pull(): ?RequestInterface;
}