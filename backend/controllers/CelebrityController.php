<?php

namespace backend\controllers;

use Yii;
use common\models\Celebrity;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CommonController;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * CelebrityController implements the CRUD actions for Celebrity model.
 */
class CelebrityController extends CommonController
{
    /**
     * @inheritdoc
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
     * Lists all Celebrity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Celebrity::find();

        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);

        $celebrities = $query->orderBy('id')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $searchModel = new Celebrity();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $params = [];
        $params['celebrities'] = $celebrities;
        $params['pagination'] = $pagination;
        $params['dataProvider'] = $dataProvider;
        $params['model'] = $searchModel;

        return $this->render('index', $params);        
    }

    /**
     * Displays a single Celebrity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Celebrity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Celebrity();

        if (Yii::$app->request->isPost) {
            // get data attributes
            $model->load(Yii::$app->request->post());
            
            if ($model->validate()) {                
                $model->imageFiles = UploadedFile::getInstance($model, 'image');
                if(!empty($model->imageFiles)){
                    $name=$model->upload();
                    $model->image=$name;
                }
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Celebrity saved successfully');
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
    }

    /**
     * Updates an existing Celebrity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                
                $model->imageFiles = UploadedFile::getInstance($model, 'image');
                
                if(!empty($model->imageFiles) && !empty($model->imageFiles->name)){
                    $name=$model->upload();
                    $model->image=$name;
                } else {
                    $model->image   =   $model->oldAttributes['image'];
                }
                
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Celebrity updated successfully');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Could not save the details. Please try again later.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Please rectify the errors');
            }
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Celebrity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['status' => 2]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Celebrity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Celebrity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Celebrity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    /**
     * Changes state of an existing Celebrity model.
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
}
