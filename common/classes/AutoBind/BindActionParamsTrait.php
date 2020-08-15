<?php

namespace common\classes\AutoBind;

use common\classes\Optional\Optional;
use yii\base\InlineAction;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;

/**
 * Trait BindActionParamsTrait
 * @package common\classes\Controller
 */
trait BindActionParamsTrait
{

    /**
     * @param $action
     * @param $params
     * @return array
     * @throws NotFoundHttpException
     * @throws \ReflectionException
     * @throws \yii\web\BadRequestHttpException
     */
    public function bindActionParams($action, $params)
    {

        $args = parent::bindActionParams($action, $params);

        if (!$action instanceof InlineAction) {
            return $args;
        }

        $method = new \ReflectionMethod($this, $action->actionMethod);

        foreach ($method->getParameters() as $index => $param) {

            $value = \Yii::$app->request->get($param->getName());

            if ($value && $param->getClass() && in_array(ActiveRecordInterface::class, $param->getClass()->getInterfaceNames())) {

                $class = $param->getClass()->getName();
                $model = $class::findOne($value);

                if ($model instanceof Optional) {
                    $model = $model->orNull();
                }

                if (!$model) {
                    throw new NotFoundHttpException('Model with ' . $param->getName() . ' ' . strip_tags($value) . ' not found.');
                }

                $args[$index] = $model;
            }
        }

        return $args;
    }


}