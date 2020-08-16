<?php

namespace backend\controllers;

use backend\models\CategorySearch;
use common\models\Category;
use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends AdminController
{
    /** @var CategorySearch */
    public $searchModel;

    /** @var Category */
    public $parentModel;

    /** @var string */
    public $parentUrlKey;

    /**
     * CategoryController constructor.
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        $this->searchModel = new CategorySearch();
        $this->searchModel->load(\Yii::$app->request->queryParams);

        $this->parentModel = Category::findOne($this->searchModel->parent_uuid)->orNull() ?? new Category();
        $this->parentUrlKey = Html::getInputName(new CategorySearch(), 'parent_uuid');

        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = $this->searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $this->searchModel,
            'dataProvider' => $dataProvider,
            'parentModel' => $this->parentModel
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uuid]);
        }

        return $this->render('create', [
            'model' => $model,
            'categoryTree' => Category::find()->root()->ordered()->all()
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->uuid])->send();
        }

        return $this->render('update', [
            'model' => $model,
            'categoryTree' => Category::find()->root()->ordered()->all()
        ]);
    }


    public function actionToggle($id)
    {
        $model = $this->findModel($id);
        $model->active = $model->active ? Category::ACTIVE_STATE_FALSE : Category::ACTIVE_STATE_TRUE;

        if ($model->save()) {
            \Yii::$app->session->setFlash('success', 'Новый статус публикации сохранен.');
        }

        return $this->redirect(\Yii::$app->request->referrer)->send();
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(\Yii::$app->request->referrer)->send();
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)->orNull()) !== null) {
            return $model;
        }

        \Yii::$app->session->setFlash('error', 'The requested page does not exist.');

        return $this->redirect(['index'])->send();
    }
}
