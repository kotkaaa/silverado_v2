<?php


namespace common\services;

use common\classes\Request\BaseRequest;
use common\classes\Request\RequestInterface;

/**
 * Class RequestService
 * @package common\services
 */
class RequestService extends \yii\base\Component
{
    /**
     * @param RequestInterface $request
     */
    public function pushRequest(RequestInterface $request): RequestInterface
    {
        return $request->push();
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface|null
     */
    public function pullRequest(RequestInterface $request): ?RequestInterface
    {
        return $request->pull();
    }
}