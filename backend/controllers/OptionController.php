<?php

namespace backend\controllers;

use Yii;
use common\models\Option;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\components\CommonController;

/**
 * OptionController implements the CRUD actions for Option model.
 */
class OptionController extends CommonController
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
     * Lists all Option models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Option::find();
        
        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);
        
        $options = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        
        $searchModel = new Option();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        $params = [];
        $params['options'] = $options;  
        $params['pagination'] = $pagination; 
        $params['dataProvider'] = $dataProvider;    
        $params['model'] = $searchModel;

        return $this->render('index', $params);
    }

    /**
     * Creates a new Option model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Option();
        
        if(Yii::$app->request->post()){
            $model->load(Yii::$app->request->post());            
            if($model->validate()){                
                if ($model->save(false)) {
                    echo '1';exit;
                } else {
                    echo '0';exit;
                }
            }
        }        
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Option model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       
        if(Yii::$app->request->post()){            
            $model->load(Yii::$app->request->post());            
            if($model->validate()){             
                if ($model->save(false)) {
                    echo '1';exit;
                } else {
                    //prd($model->getErrors());
                    echo '0';exit;
                }
            } else {
                //prd($model->getErrors());
            }
        }
        
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Option model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Option the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Option::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
        
    /**
     * Changes state of an existing Option model.
     * If status change is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $prevState aka previous state
     * @return mixed
     */
    public function actionStatus($id,$prevState)
    {
        $this->findModel($id)->updateAttributes(['status'=>($prevState==0) ? 1 : 0]);
        return $this->redirect(['index']);
    }
    
    /**
     * Deletes an existing Option model.
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
