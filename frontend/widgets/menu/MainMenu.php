<?php


namespace frontend\widgets\menu;


use common\services\CategoryService;
use yii\base\Widget;

/**
 * Class MainMenuWidget
 * @package frontend\widgets
 */
class MainMenu extends Widget
{
    /** @var CategoryService */
    public $categoryService;

    /** @var string */
    public $cacheId;

    /** @var int */
    public const CACHE_LIFETIME = 3600;

    /**
     * MainMenuWidget constructor.
     * @param CategoryService $categoryService
     * @param array $config
     */
    public function __construct(CategoryService $categoryService, $config = [])
    {
        $this->categoryService = $categoryService;
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->cacheId = \Yii::$app->cache->buildKey(self::class);
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        return \Yii::$app->cache->getOrSet($this->cacheId, function () {
            return $this->render('_main', [
                'items' => $this->categoryService->find()->ordered()->all()
            ]);
        }, self::CACHE_LIFETIME);
    }
}