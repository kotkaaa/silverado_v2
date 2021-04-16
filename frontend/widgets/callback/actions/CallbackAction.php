<?php

namespace frontend\widgets\callback\actions;

use common\classes\Request\CallbackRequest;
use common\services\RequestService;
use frontend\widgets\callback\models\CallbackForm;
use yii\base\InvalidConfigException;

/**
 * Class CallbackAction
 * @package frontend\widgets\callback\actions
 */
class CallbackAction extends \yii\base\Action
{
    /** @var string */
    public $view;

    /** @var RequestService */
    public $requestService;

    /**
     * CallbackAction constructor.
     * @param $id
     * @param $controller
     * @param RequestService $requestService
     * @param array $config
     */
    public function __construct($id, $controller, RequestService $requestService, $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->requestService = $requestService;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->view) {
            throw new InvalidConfigException('View not set.');
        }

        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $model = new CallbackForm();
        $request = null;

        if ($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            /** @var CallbackRequest $request */
            $request = $this->requestService->pushRequest(new CallbackRequest([
                'storage' => CallbackRequest::STORAGE_COOKIE,
                'phone' => $model->phone,
                'name' => $model->name
            ]));

            $request->send();
        }

        return $this->controller->renderAjax($this->view, [
            'model' => $model,
            'request' => $request ?? $this->requestService->pullRequest(new CallbackRequest(['storage' => CallbackRequest::STORAGE_COOKIE]))
        ]);
    }
}