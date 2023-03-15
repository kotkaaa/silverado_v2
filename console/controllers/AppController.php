<?php


namespace console\controllers;

use common\models\Attribute;
use common\models\AttributeValue;
use common\models\Category;
use common\models\Option;
use common\models\OptionValue;
use common\models\Product;
use common\models\ProductAttribute;
use common\models\ProductOption;
use common\services\ImportService;

/**
 * Class AppController
 * @package console\controllers
 */
class AppController extends \yii\console\Controller
{
    /** @var ImportService */
    public $importService;

    /**
     * AppController constructor.
     * @param $id
     * @param $module
     * @param ImportService $importService
     * @param array $config
     */
    public function __construct($id, $module, ImportService $importService, $config = [])
    {
        parent::__construct($id, $module, $config);
	    $this->importService = $importService;
    }

    /**
     * @return void
     */
    public function actionImportPrice()
    {
        $rows = $this->importService->readSpreadsheetAsArray('Q3YSxi5UEtzUBy5mxL93eqxcsH7WlbF7fOk3RHZ58Vp9q34PtfCE-xV-RHKTaK_i9kZoUoZbUTiemk', 'remote');
        $affected = 0;

        foreach ($rows as $row)
        {
            /** @var Product $model */
            $model = Product::findOne(['sku' => $row['Артикул']])->orElse(function () use ($row) {
                return $model = new Product([
                    'sku' => $row['Артикул'],
                    'title' => "{$row['Название']} {$row['Артикул']}",
                    'price' => floatval($row['Цена Продажа']),
                ]);
            });

            if (!$model->save()) {
                echo 'Товар ' . $model->sku . ' не сохранен. Причина: ' . implode('; ', array_values($model->getErrorSummary(true))) . PHP_EOL;
                continue;
            }

            $category = Category::findOne(['title' => $row['Категория']])->orNull();

            if ($category) {
                $model->link('category', $category);
            }

            foreach ($row as $colname => $value) {
                // Skip product model columns
                if (in_array($colname, ['Артикул', 'Название', 'Цена Закупка', 'Цена Продажа', 'Цена Акция'])) {
                    continue;
                }

                $value = explode(',', $value);

                @array_walk($value, 'trim');
                @array_walk($value, function (&$val, $key) {
                    $val = preg_replace('/\n/', '', $val);
                    $val = trim($val);
                });

                switch ($colname) {
                    // Options
                    case 'Размер':
						$pos = 0;

                        foreach ($value as $val) {
                            $option = Option::find()
                                ->andWhere(['title' => $colname])
                                ->one();

							if (!$option && in_array($colname, ['Размер'])) {
								$pos++;

								$option = new Option([
									'title' => $colname,
									'required' => 1,
									'position' => $pos
								]);

								$option->save();
							} elseif (!$option) {
                                continue;
                            }

                            /** @var OptionValue $optionValue */
                            $optionValue = OptionValue::find()
                                ->innerJoinWith('option')
                                ->andWhere([
                                    'option_value.title' => $val,
                                    'option.title' => $colname
                                ])
                                ->one()
                                ->orElse(function () use ($val, $option) {
                                    return $optionValue = new OptionValue([
                                        'option_uuid' => $option->uuid,
                                        'title' => $val
                                    ]);
                                });

                            if ($optionValue->isNewRecord) {
                                $optionValue->save();
                            }

                            $productOption = new ProductOption([
                                'product_uuid' => $model->uuid,
                                'value_uuid' => $optionValue->uuid
                            ]);

                            $productOption->save();
                        }

                        break;

                    // Attributes
                    default:
						$pos = 0;

                        foreach ($value as $val) {
                            $attribute = Attribute::find()
	                            ->andWhere(['title' => $colname])
	                            ->one();

							if (!$attribute && in_array($colname, ['Застежка', 'Тип вставки', 'Форма камня', 'Для кого', 'Металл', 'Металл 2', 'Средний вес', 'Камень', 'Цвет'])) {
								$pos++;

								$attribute = new Attribute([
									'title' => $colname,
									'position' => $pos
								]);

								$attribute->save();
							} elseif (!$attribute) {
                                continue;
                            }

                            /** @var AttributeValue $attributeValue */
                            $attributeValue = AttributeValue::find()
                                ->innerJoinWith('attributeModel')
                                ->andWhere([
                                    'attribute_value.title' => $val,
                                    'attribute.title' => $colname
                                ])
                                ->one()
                                ->orElse(function () use ($val, $attribute) {
                                    return $attributeValue = new AttributeValue([
                                        'attribute_uuid' => $attribute->uuid,
                                        'title' => $val
                                    ]);
                                });

                            if ($attributeValue->isNewRecord) {
                                $attributeValue->save();
                            }

                            $productAttribute = new ProductAttribute([
                                'product_uuid' => $model->uuid,
                                'value_uuid' => $attributeValue->uuid
                            ]);

                            $productAttribute->save();
                        }
                        break;
                }
            }

            echo 'Товар ' . $model->sku . ' сохранен.' . PHP_EOL;

            $affected++;
        }

        echo 'Импортировано ' . $affected . ' товаров.' . PHP_EOL;
    }
}