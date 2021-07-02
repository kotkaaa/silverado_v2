<?php

namespace frontend\widgets\product\size;

/**
 * Class SizeChecker
 * @package frontend\widgets\product
 */
class SizeChecker extends \yii\base\Widget
{
    /**
     * @return string
     */
    public function run()
    {
        return $this->render('index');
    }
}