<?php


namespace frontend\widgets\modal;

/**
 * Class MainModal
 * @package frontend\widgets\modal
 */
class MainModal extends \yii\base\Widget
{
    /** @var string */
    public $modal_id = 'main-modal';

    /** @var string|null */
    public $header = '<span></span>';

    /** @var string|null */
    public $content;

    /**
     * @return string
     * @throws \yii\base\InvalidArgumentException
     */
    public function run()
    {
        return $this->render('index', [
            'modal_id' => $this->modal_id,
            'header' => $this->header,
            'content' => $this->content
        ]);
    }
}