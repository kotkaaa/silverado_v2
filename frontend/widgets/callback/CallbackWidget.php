<?php


namespace frontend\widgets\callback;

use common\classes\Request\CallbackRequest;
use common\services\RequestService;
use frontend\widgets\callback\models\CallbackForm;

/**
 * Class CallbackWidget
 * @package frontend\widgets\callback
 */
class CallbackWidget extends \yii\base\Widget
{
    /** @var RequestService */
    public $requestService;

    /**
     * CallbackWidget constructor.
     * @param RequestService $requestService
     * @param array $config
     */
    public function __construct(RequestService $requestService, $config = [])
    {
        parent::__construct($config);
        $this->requestService = $requestService;
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('index', [
            'model' => new CallbackForm(),
            'request' => $this->requestService->pullRequest(new CallbackRequest(['storage' => CallbackRequest::STORAGE_COOKIE]))
        ]);
    }
}