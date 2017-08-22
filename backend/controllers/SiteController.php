<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\ChangePassword;
use common\models\Setting;
use common\components\CommonController;
use common\models\User;
use common\models\Publication;
use common\models\Author;
use yii\web\UploadedFile;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','forget-password','reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','change-password','profile','setting'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $totalPublication=Publication::find()->where(['<>','status',2])->count();
        $totalUser=User::find()->where(['<>','status',2])->andWhere(['=','user_type',1])->count();
        $totalAuthor=Author::find()->where(['<>','status',2])->count();
        return $this->render('index', [
            'totalPublication' => $totalPublication,'totalUser' => $totalUser,'totalAuthor' => $totalAuthor]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {

        $model = new LoginForm();
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if(Yii::$app->request->post()){
            $model->load(Yii::$app->request->post());
            if ($model->login()) {

                $LogedinUser=User::findOne(Yii::$app->user->id);
                $LogedinUser->scenario = 'update_login_date';
                $LogedinUser->last_login= date('Y-m-d H:i:s');
                $LogedinUser->save();
                $this->goBack();
            
            }
        }
        
        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionForgetPassword()
    {
        $this->layout = 'main-login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        return $this->render('forgetPassword', [
            'model' => $model,
        ]);
    }
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'main-login';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    /*
        # Ravi Joshi
        # For change the password of admin user 
    */
    public function actionChangePassword(){      

        $model = new ChangePassword(Yii::$app->user->id);
        $model->scenario = 'changepassword';
        
        if (Yii::$app->request->post()) {   
         
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                
                $User = $model->updatePassword();                
                Yii::$app->session->setFlash('success', 'Password updated successfully.');
            }
        } 
        return $this->render('changepassword',['model'=>$model]);
    } 
    /*
        # Ravi Joshi
        # For update the profile of admin user 
     * 
    */
    public function actionProfile()
    {
        $model = User::findIdentity(Yii::$app->user->id);
        
        if(Yii::$app->request->post()){             
            $model->scenario = 'adminedit';
            $model->load(Yii::$app->request->post());   
            if($model->validate()){  
                $model->imageFiles = UploadedFile::getInstance($model, 'image');  
                if(!empty($model->imageFiles)){
                    $name=$model->upload();
                    $model->image=$name;                    
                }else {
                    $model->image   =   $model->oldAttributes['image'];
                }
                if($model->save()){
                    Yii::$app->user->identity->first_name = $model->first_name;
                    Yii::$app->user->identity->last_name = $model->last_name;
                    Yii::$app->user->identity->image = $model->image;
                    Yii::$app->getSession()->setFlash('success', 'Profile updated successfully.');
                }
            }
        }
        return $this->render('profile',['model'=>$model]);
    }
   
    /*
        # Ravi Joshi
        # For update the web setting 
    */
    public function actionSetting()
    {
        $model= new Setting();
        
        if(Yii::$app->request->post()){ 
           $data=$_POST['Setting'];             
           $model->load(Yii::$app->request->post());            
           $model->Site_Name=$data['Site_Name'];
           $model->Admin_Email=$data['Admin_Email'];
           $model->Front_Per_Page_Record=$data['Front_Per_Page_Record'];
            if($model->validate()){  

                foreach ($_POST['Setting'] as $key => $value) {
                    $settingData = Setting::findOne(array('key' =>$key));
                    if (!empty($settingData->id)){
                        $settingData->Site_Name = $model->Site_Name;
                        $settingData->Admin_Email = $model->Admin_Email;
                        $settingData->Front_Per_Page_Record = $model->Front_Per_Page_Record;
                    }
                    if($settingData->input_type=='text'){
                        if (!empty($settingData->id)){
                            $settingData->value = $value;
                        }    
                        if($settingData->save()){
                            Yii::$app->session->setFlash('success', 'Settings Updated successfully');
                        }
                    }
                }
                $model->imageFiles = UploadedFile::getInstance($model, 'Invester_guide');  
                
                if(!empty($model->imageFiles)){
                    $name=$model->upload();
                    $settingData = Setting::findOne(array('key' =>'Invester_guide'));
                
                    if (!empty($settingData->id)){
                        $settingData->Site_Name = $model->Site_Name;
                        $settingData->Admin_Email = $model->Admin_Email;
                        $settingData->Front_Per_Page_Record = $model->Front_Per_Page_Record;
                    }
                
                    if($settingData->input_type=='file'){
                        if (!empty($settingData->id)){
                            $settingData->value = $name;
                        }    
                        if($settingData->save()){
                            Yii::$app->session->setFlash('success', 'Settings Updated successfully');
                        }
                    }
                }
        } 
        }
        $list = Setting::find(['status'=>1])->orderBy(['setting_order'=>SORT_ASC])->all();
        return $this->render('setting',['list'=>$list,'model'=>$model]);
    }
}
