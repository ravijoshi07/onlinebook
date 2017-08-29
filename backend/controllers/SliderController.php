<?php

namespace backend\controllers;

use Yii;
use common\models\Slider;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for Slider model.
 */
class SliderController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
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
     * Lists all Slider models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $query = Slider::find();
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
            'pageSize' => 10,
        ]);
        $slider = $query->orderBy('image')
            ->where(['<>','status',2])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $searchModel = new Slider();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $params = [];
        $params['slider'] = $slider;  
        $params['pagination'] = $pagination; 
        $params['dataProvider'] = $dataProvider;    
        $params['model'] = $searchModel;
        return $this->render('index', $params);
    }

    /**
     * Displays a single Slider model.
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
        $model = new Slider();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {                
                $model->imageFiles = UploadedFile::getInstance($model, 'image');
                if(!empty($model->imageFiles)){
                    $name=$model->upload();
                    $model->image=$name;
                }
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Slider saved successfully');
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
     * Updates an existing Slider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->post()){
            $model->setScenario('update');
            $model->load(Yii::$app->request->post());
            if($model->validate()){ 
                $model->imageFiles = UploadedFile::getInstance($model, 'image');
                if(!empty($model->imageFiles)){
                    $name=$model->upload();
                    $model->image=$name;
                }else{
                       $model->image   =   $model->oldAttributes['image'];
                }
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Slider updated successfully');
                } else {
                    Yii::$app->session->setFlash('error', 'Could not update the details. Please try again later.');
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
     * Finds the Slider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Slider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slider::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Changes state of an existing Slider model.
     * If status change is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $prevState aka previous state
     * @return mixed
     */
    public function actionStatus($id,$prevState)
    {
        $this->findModel($id)->updateAttributes(['status'=>($prevState==0 || $prevState==3) ? 1 : 0]);
        return $this->redirect(['index']);
    }
    
    /**
     * Deletes an existing Slider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['status'=>2]);
        return $this->redirect(['index']);
    }
}
