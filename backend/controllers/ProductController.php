<?php

namespace backend\controllers;

use backend\models\ProductSearch;
use common\models\Attribute;
use common\models\Category;
use common\models\Option;
use common\models\Product;
use common\models\ProductFiles;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends AdminController
{
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uuid])->send();
        }

        return $this->render('create', [
            'model' => $model,
            'categoryTree' => Category::find()->root()->ordered()->all(),
            'options' => Option::find()->all(),
            'attributes' => Attribute::find()->all(),
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $files = \Yii::$app->request->post('ProductFiles', []);

            for ($i = 0; $i < count($files); $i++) {
                if (($productFile = ProductFiles::findOne($files[$i]['uuid'])) === null) {
                    continue;
                }

                $productFile->position = $i;
                $productFile->save(false);
            }

            return $this->redirect(['view', 'id' => $model->uuid])->send();
        }

        return $this->render('update', [
            'model' => $model,
            'categoryTree' => Category::find()->root()->ordered()->all(),
            'options' => Option::find()->all(),
            'attributes' => Attribute::find()->all(),
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index'])->send();
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionToggle($id)
    {
        $model = $this->findModel($id);
        $model->active = $model->active ? Product::ACTIVE_STATE_FALSE : Product::ACTIVE_STATE_TRUE;

        if ($model->save()) {
            \Yii::$app->session->setFlash('success', 'Новый статус публикации сохранен.');
        }

        return $this->redirect(\Yii::$app->request->referrer)->send();
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)->orNull()) !== null) {
            return $model;
        }

        \Yii::$app->session->setFlash('error', 'The requested page does not exist.');

        return $this->redirect(['index'])->send();
    }

    /**
     * @param $id
     * @return string|null
     * @throws NotFoundHttpException
     */
    public function actionFileUpload($id)
    {
        $model = $this->findModel($id);
        $files = [];

        $model->trigger(Product::EVENT_AFTER_FILE_UPLOAD);
        $model->refresh();

        foreach ($model->productFiles as $productFile) {
            $files[] = [
                'uuid' => $productFile->uuid,
                'name' => $productFile->files->name,
                'size' => $productFile->files->size,
                'mime' => $productFile->files->mime,
                'url' => implode('/', [\Yii::$app->params['frontUrl'], $productFile->files->url, $productFile->files->name]),
                'thumbnailUrl' => implode('/', [\Yii::$app->params['frontUrl'], $productFile->files->url, $productFile->files->name]),
                'deleteUrl' => Url::to(['/product/delete-uploaded-file', 'id' => $productFile->uuid]),
                'deleteType' => 'POST',
            ];
        }

        return Json::encode(['files' => $files]);
    }

    /**
     * @param $term
     * @return array|\yii\web\Response
     */
    public function actionSearch($term)
    {
        $searchModel = new ProductSearch(['title' => $term, 'sku' => $term]);
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;

        if (\Yii::$app->request->isAjax) {

            $rows = ArrayHelper::map($dataProvider->getModels(), 'uuid', 'title', 'category.title');

            $results = [];

            foreach ($rows as $category => $products) {
                $children = [];

                foreach ($products as $uuid => $title) {
                    $children[] = ['id' => $uuid, 'text' => $title];
                }

                $results[] = ['text' => $category, 'children' => $children];
            }

            return $this->asJson([
                'results' => $results
            ]);
        }

        return $dataProvider->getModels();
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteUploadedFile($id)
    {
        if (($file = ProductFiles::findOne($id)) == null) {
            throw new NotFoundHttpException('Uploaded file does not exist.');
        }

        $file->delete();

        return $this->redirect(\Yii::$app->request->referrer)->send();
    }
}
