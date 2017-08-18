<?php

namespace backend\controllers;

use Yii;
use app\models\CmsPage;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use common\components\CommonController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * CmsPageController implements the CRUD actions for CmsPage model.
 */
class CmsPageController extends CommonController{

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all CmsPage models.
     * @return mixed
     */
    public function actionIndex() {
        /* $dataProvider = new ActiveDataProvider([
          'query' => CmsPage::find()->where('status IN (0,1)'),
          ]);

          return $this->render('index', [
          'dataProvider' => $dataProvider,
          'model' => new CmsPage()
          ]); */

        $query = CmsPage::find();

        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);

        $cmspages = $query->orderBy('id')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $searchModel = new CmsPage();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $params = [];
        $params['cmspages'] = $cmspages;
        $params['pagination'] = $pagination;
        $params['dataProvider'] = $dataProvider;
        $params['model'] = $searchModel;

        return $this->render('index', $params);
    }

    /**
     * Displays a single CmsPage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CmsPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new CmsPage();

        if (Yii::$app->request->isPost) {
            // get data attributes
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Page saved successfully');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Could not save the details. Please try again later.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Please rectify the errors');
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
        /*
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['view', 'id' => $model->id]);
          } else {
          if(!empty($model->errors)){
          prd($model->errors);
          }
          return $this->render('create', [
          'model' => $model,
          ]);
          } */
    }

    /**
     * Updates an existing CmsPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Page updated successfully');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CmsPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        //$this->findModel($id)->delete();
        $this->findModel($id)->updateAttributes(['status' => 2]);
        return $this->redirect(['index']);
    }

    /**
     * Changes state of an existing CmsPage model.
     * If status change is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $prevState aka previous state
     * @return mixed
     */
    public function actionStatus($id, $prevState) {
        //$this->findModel($id)->delete();
        $this->findModel($id)->updateAttributes(['status' => ($prevState == 0) ? 1 : 0]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the CmsPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CmsPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = CmsPage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
