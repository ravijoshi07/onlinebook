<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\components\CommonController;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CommonController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = User::find();
        
        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);
        
        $users = $query->orderBy('username')
            ->where(['<>','status','2'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        
        $searchModel = new User();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        $params = [];
        $params['users'] = $users;  
        $params['pagination'] = $pagination; 
        $params['dataProvider'] = $dataProvider;    
        $params['model'] = $searchModel;

        return $this->render('index', $params);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        
        if(Yii::$app->request->post()){
            $model->load(Yii::$app->request->post());
            
            $model->auth_key        =   Yii::$app->security->generateRandomString();
            $model->op_password     =   $model->password_hash;
            //$model->setPassword($model->password_hash);
            
            if($model->validate()){
                $model->setPassword($model->password_hash);
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
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('paswd_update');
        
        if(Yii::$app->request->post()){
            
            $model->load(Yii::$app->request->post());
            // if(!empty($model->password_hash)){ 
            //     //$model->auth_key        =   Yii::$app->security->generateRandomString();
            //     //$model->op_password     =   $model->password_hash;
            //     //$model->setPassword($model->password_hash);
            // } else {
            //     $model->setScenario('update');
            //     unset($model->auth_key);
            //     unset($model->password_hash);
            // }
            
            //prd($model->confirm_password);
            $model->username=$model->oldAttributes['username'];
            $model->email=$model->oldAttributes['email'];
            if($model->validate()){ 
                // if(!empty($model->password_hash)){ 
                //     $model->setPassword($model->password_hash);
                // }
                if ($model->save(false)) {
                    echo '1';exit;
                } else {
                    //prd($model->getErrors());
                    echo '0';exit;
                }
            } else {
                //prd($model->getErrors());
            }
        } else {
            $model->password_hash   =   ""; 
        }
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Changes state of an existing User model.
     * If status change is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $prevState aka previous state
     * @return mixed
     */
    public function actionStatus($id,$prevState)
    {
        //$this->findModel($id)->delete();
        $this->findModel($id)->updateAttributes(['status'=>($prevState==0 || $prevState==3) ? 1 : 0]);
        return $this->redirect(['index']);
    }
    
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $prevState  =   $this->findModel($id);
        $this->findModel($id)->updateAttributes([
            'status'=>2,
        ]);
        return $this->redirect(['index']);
    }
}
