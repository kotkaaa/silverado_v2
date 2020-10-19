<?php

namespace backend\controllers;

use backend\models\OrderForm;
use common\models\OrderInfo;
use common\models\Product;
use common\services\PurchaseService;
use Yii;
use common\models\Order;
use backend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /** @var PurchaseService */
    public $purchaseService;

    /**
     * OrderController constructor.
     * @param $id
     * @param $module
     * @param PurchaseService $purchaseService
     * @param array $config
     */
    public function __construct($id, $module, PurchaseService $purchaseService, $config = [])
    {
        $this->purchaseService = $purchaseService;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = OrderForm::getInstance();

        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            $orderInfo = new OrderInfo();
            $orderInfo->setAttributes($form->getAttributes());

            if ($orderInfo->validate() && (($order = new Order()) !== null && $order->save()))
            {
                $order->link('orderInfo', $orderInfo);

                \Yii::$app->session->setFlash('success', 'Заказ №' . $order->id . ' сохранен.');

                return $this->redirect(['view', 'id' => $order->id])->send();
            };
        }

        return $this->render('create', [
            'orderForm' => $form
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $order = $this->findModel($id);
        $form = OrderForm::getInstance();

        $form->setAttributes([
            'status' => $order->status,
            'source' => $order->source,
            'user_name' => $order->orderInfo->user_name,
            'user_email' => $order->orderInfo->user_email,
            'user_phone' => $order->orderInfo->user_phone,
            'comment' => $order->orderInfo->comment,
            'note' => $order->orderInfo->note,
            'payment_type' => $order->orderInfo->payment_type,
            'delivery_type' => $order->orderInfo->delivery_type,
            'location' => $order->orderInfo->location,
            'recepient_name' => $order->orderInfo->recepient_name,
            'recepient_phone' => $order->orderInfo->recepient_phone,
            'custom_recepient' => !empty($order->orderInfo->recepient_name . $order->orderInfo->recepient_phone)
        ], false);

        if ($order->orderInfo->delivery_type == OrderInfo::DELIVERY_METHOD_COURIER) {
            $form->address_2 = $order->orderInfo->address;
        } elseif ($order->orderInfo->delivery_type == OrderInfo::DELIVERY_METHOD_POST) {
            $form->address_1 = $order->orderInfo->address;
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            $order->setAttributes($form->getAttributes());
            $order->orderInfo->setAttributes($form->getAttributes());

            if ($order->orderInfo->save() && $order->save())
            {
                \Yii::$app->session->setFlash('success', 'Заказ №' . $order->id . ' сохранен.');
                return $this->redirect(['view', 'id' => $order->id])->send();
            };
        }

        return $this->render('update', [
            'model' => $order,
            'orderForm' => $form
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $term
     * @return \yii\web\Response
     */
    public function actionSearchCity($term)
    {
        $out = ['results' => []];

        foreach ($this->purchaseService->findCity($term) as $city) {
            $out['results'][] = [
                'id' => $city['DescriptionRu'],
                'text' => $city['DescriptionRu'],
                'ref' => $city['Ref']
            ];
        }

        return $this->asJson($out);
    }

    /**
     * @param $term
     * @return \yii\web\Response
     */
    public function actionSearchWarehouse($term)
    {
        $out = ['results' => []];

        foreach ($this->purchaseService->findWarehouse($term) as $warehouse) {
            $out['results'][] = [
                'id' => $warehouse['DescriptionRu'],
                'text' => $warehouse['DescriptionRu'],
                'ref' => $warehouse['Ref']
            ];
        }

        return $this->asJson($out);
    }
}
