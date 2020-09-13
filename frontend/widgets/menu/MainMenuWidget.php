<?php


namespace frontend\widgets\menu;


use common\services\CategoryService;
use yii\bootstrap\Nav;

/**
 * Class MainMenuWidget
 * @package frontend\widgets
 */
class MainMenuWidget extends Nav
{
    /** @var CategoryService */
    public $categoryService;

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
     * @return string
     */
    public function run()
    {
        foreach ($this->categoryService->find()->ordered()->all() as $category) {
            $this->items[] = [
                'label' => $category->title,
                'url' => ['/category/' . $category->alias]
            ];
        }

        return parent::run();
    }
}