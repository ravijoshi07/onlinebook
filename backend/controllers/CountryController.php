<?php

namespace backend\controllers;

use Yii;
use common\models\Country;
use common\models\State;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\components\CommonController;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends CommonController
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
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Country::find();

        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);

        $Country = $query->orderBy('id')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $searchModel = new Country();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $params = [];
        $params['categories'] = $Country;
        $params['pagination'] = $pagination;
        $params['dataProvider'] = $dataProvider;
        $params['model'] = $searchModel;

        return $this->render('index', $params);
    }

    /**
     * Changes state of an existing Country model.
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
     * Lists all State models.
     * @return mixed
     */
    public function actionState($country_id)
    {
        $query = State::find()->andWhere(['=','country_id',$country_id]);
        
        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);

        $State = $query->orderBy('id')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $searchModel = new State();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $params = [];
        $params['subcategories'] = $State;
        $params['pagination'] = $pagination;
        $params['dataProvider'] = $dataProvider;
        $params['model'] = $searchModel;

        $country = Country::findOne(['=','id',$country_id]);
        
        $params['country'] = $country;
        
        return $this->render('state', $params);
    }


    /**
     * Creates a new State model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionStatecreate($country_id)
    {
        $model = new State();
        if(Yii::$app->request->post()){
            $model->load(Yii::$app->request->post());
            
            $model->country_id =   $country_id;
            
            if($model->validate()){
                if ($model->save()) {
                    echo '1';exit;
                } else {
                    echo '0';exit;
                }
            }
        }        
        return $this->renderAjax('_form_state', [
            'model' => $model,
            'country_id' => $country_id
        ]);
        
    }

    /**
     * Updates an existing State model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStateupdate($id)
    {
        $model = $this->findStateModel($id);
        if(Yii::$app->request->post()){
            $model->load(Yii::$app->request->post());
            if($model->validate()){ 
                if ($model->save()) {
                    echo '1';exit;
                } else {
                    echo '0';exit;
                }
            }
        }
        return $this->renderAjax('_form_state', [
            'model' => $model,
            'country_id' => $model->country_id
        ]);
        
    }

    /**
     * Deletes an existing State model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStatedelete($id)
    {
        $dataRow    =   $this->findStateModel($id);
        $this->findStateModel($id)->updateAttributes(['status'=>2]);
        return $this->redirect(['state?country_id='.$dataRow->country_id]);
    }

    /**
     * Finds the State model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return State the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findStateModel($id)
    {
        if (($model = State::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Changes state of an existing State model.
     * If status change is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $prevState aka previous state
     * @return mixed
     */
    public function actionStatestatus($id,$prevState)
    {
        $dataRow    =   $this->findStateModel($id);
        $this->findStateModel($id)->updateAttributes(['status'=>($prevState==0) ? 1 : 0]);
        return $this->redirect(['state?country_id='.$dataRow->country_id]);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
