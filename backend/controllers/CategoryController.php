<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use common\models\Subcategory;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\components\CommonController;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends CommonController
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Category::find();

        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);

        $Category = $query->orderBy('id')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $searchModel = new Category();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $params = [];
        $params['categories'] = $Category;
        $params['pagination'] = $pagination;
        $params['dataProvider'] = $dataProvider;
        $params['model'] = $searchModel;

        return $this->render('index', $params);
    }


    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
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
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing Category model.
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
                if ($model->save()) {
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
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['status'=>2]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Changes state of an existing Category model.
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
     * Lists all Subcategory models.
     * @return mixed
     */
    public function actionSubcategory($category_id)
    {
        $query = Subcategory::find()->andWhere(['=','category_id',$category_id]);
        
        $pagination = new Pagination([
            'defaultPageSize' => 2,
            'totalCount' => $query->count(),
            'pageSize' => 2,
        ]);

        $Subcategory = $query->orderBy('id')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

        $searchModel = new Subcategory();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        $params = [];
        $params['subcategories'] = $Subcategory;
        $params['pagination'] = $pagination;
        $params['dataProvider'] = $dataProvider;
        $params['model'] = $searchModel;

        $category = Category::findOne(['=','id',$category_id]);
        
        $params['category'] = $category;
        
        return $this->render('subcategory', $params);
    }


    /**
     * Creates a new Subcategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSubcategorycreate($category_id)
    {
        $model = new Subcategory();
        if(Yii::$app->request->post()){
            $model->load(Yii::$app->request->post());
            
            $model->category_id =   $category_id;
            
            if($model->validate()){
                if ($model->save()) {
                    echo '1';exit;
                } else {
                    echo '0';exit;
                }
            }
        }        
        return $this->renderAjax('_form_subcategory', [
            'model' => $model,
            'category_id' => $category_id
        ]);
        
    }

    /**
     * Updates an existing Subcategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubcategoryupdate($id)
    {
        $model = $this->findSubcategoryModel($id);
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
        return $this->renderAjax('_form_subcategory', [
            'model' => $model,
            'category_id' => $model->category_id
        ]);
        
    }

    /**
     * Deletes an existing Subcategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubcategorydelete($id)
    {
        $dataRow    =   $this->findSubcategoryModel($id);
        $this->findSubcategoryModel($id)->updateAttributes(['status'=>2]);
        return $this->redirect(['subcategory?category_id='.$dataRow->category_id]);
    }

    /**
     * Finds the Subcategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subcategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSubcategoryModel($id)
    {
        if (($model = Subcategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Changes state of an existing Subcategory model.
     * If status change is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $prevState aka previous state
     * @return mixed
     */
    public function actionSubcategorystatus($id,$prevState)
    {
        $dataRow    =   $this->findSubcategoryModel($id);
        $this->findSubcategoryModel($id)->updateAttributes(['status'=>($prevState==0) ? 1 : 0]);
        return $this->redirect(['subcategory?category_id='.$dataRow->category_id]);
    }
}
