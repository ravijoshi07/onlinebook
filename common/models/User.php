<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_UNACTIVE = 3;
    public $confirm_password;
    public $imageFiles;

            
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
   
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['username','email','first_name','last_name','phone','password_hash'];
        $scenarios['update_login_date'] = ['last_login'];
        $scenarios['paswd_update'] = ['username','email','first_name','last_name','phone','password_hash','confirm_password'];
        $scenarios['adminedit'] = ['image','email','first_name','last_name'];
        return $scenarios;
    }

        /**
     * @inheritdoc
     */
    public function rules()
    {   
        return [
            [['username', 'auth_key', 'email', 'first_name', 'last_name'], 'required'],
            
            [['password_hash', 'confirm_password'], 'required', 'on'=>'default'],
            [['password_hash'], 'required', 'on'=>'paswd_update'],
            
            [['password_hash', 'confirm_password'], 'string', 'min'=>6, 'skipOnEmpty'=>true, 'on'=>'update'],
            
            [['confirm_password'], 'compare', 'compareAttribute' => 'password_hash'],
                
            [['first_name','last_name'], 'match','pattern' => '/^[A-Za-z ]+$/u','message' => '{attribute} can only contain word characters'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'image'], 'string', 'max' => 255],
            
            [['first_name','last_name'], 'string', 'max' => 50, 'min' => 2],
            
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique', 'filter'=>['<>','status','2']],
            
            ['username', 'string', 'min' => 2],
            [['email','username'], 'trim'],            
            
            [['email'], 'unique', 'filter'=>['<>','status','2']],
            
            [['password_reset_token'], 'unique', 'filter'=>['<>','status','2']],
            [['activation_code'], 'string', 'max' => 50],
            ['phone', 'string', 'max' => 20],
            [['phone'], 'integer'],
            
            [['email','username'],'customUnique'],
            
            ['status', 'default', 'value' => self::STATUS_UNACTIVE],
            ['user_type', 'default', 'value' => 1], // front user
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_UNACTIVE]],
            ['activation_code', 'default', 'value' => $this->getActivationCode()],
            [['created_at','updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['auth_key','imageFiles'],'safe','on'=>['adminedit']],
            ['imageFiles', 'image', 'minWidth' => 50,'minHeight' => 50, 'extensions' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 2,'on'=>['adminedit']],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password_hash' => 'Password',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = 1)
    {
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return static::findOne(['auth_key' => $token,'user_type'=>$type,'status'=>1]);
    }
        

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email,$role)
    {   
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE,'user_type'=>$role]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function getActivationCode(){
        $codeHere   =   Yii::$app->getSecurity()->generateRandomString(15,true);
        $dataNow    =   User::find()->where(['activation_code'=>$codeHere])->count();
        
        if(!empty($dataNow)){
            return $this->getActivationCode();
        } else {
            return $codeHere;
        }
    }
        
    public function checkMalformedPhone() {
        // get if there's + already in the field in any case
        prd('yes');
        $newStr =   str_replace('+', '', $this->phone );
        if($newStr!==$this->phone){
            $this->addError('phone',"Malformed Phone number don't prepend with '+'."); 
            return false;
        }
    }
 
    
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if(isset(Yii::$app->user->identity->id)){ 
            $id = Yii::$app->user->identity->id;
        } else {
            $id = '';
        }
        
        $query = User::find()
                ->andWhere(['<>','id', $id ])
                ->andWhere(['<>','status','2']);
        
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,            
            'pagination' => [
                'pageSize' => 2,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                //'id' => SORT_ASC,
            ],
            'attributes' => [   
                'first_name' => [
                    'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                    'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                    'default' => SORT_ASC,
                ], 
                'last_name' => [
                    'asc' => ['last_name' => SORT_ASC, 'last_name' => SORT_ASC],
                    'desc' => ['last_name' => SORT_DESC, 'last_name' => SORT_DESC],
                    'default' => SORT_ASC,
                ], 
                'last_login' => [
                    'asc' => ['last_login' => SORT_ASC],
                    'desc' => ['last_login' => SORT_DESC],
                    'default' => SORT_ASC,
                ],  
                'email',
            ]
        ]);


         // load the search form data and validate
        if (!($this->load($params))) {
            return $dataProvider;
        }
           

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'last_login', $this->last_login]);
            //->andFilterWhere(['<>','status','2']);
        
              
        
        return $dataProvider;
    }    
    
    
    public function beforeSave($insert) {
        
        if (parent::beforeSave($insert)) {
            
            $this->updated_at   =   date('Y-m-d H:i:s');
            
            if ($this->isNewRecord){
                $this->created_at   =   date('Y-m-d H:i:s');
            }
            
            return true;
        } else {
            return false;
        }
    }
    public function upload()
    {
        if ($this->validate()) { 
            $name = md5(uniqid(rand(), true)) . '.' . $this->imageFiles->extension;
            $admin_name = 'admin_'.$name;
            $this->imageFiles->saveAs(Yii::getAlias('@main/uploads/admin/') . $admin_name);
            return $admin_name;
        } else {
            return '';
        }
    }
    
    public function customUnique($attribute){        
        if($this->isNewRecord){
            $prevData   =   User::find()->where([$attribute=>$this->$attribute])
                            ->andWhere(['<>','status','2'])
                            ->one();
        } else {
            $prevData   =   User::find()->where([$attribute=>$this->$attribute])
                            ->andWhere(['<>','status','2'])
                            ->andWhere(['<>','id',$this->id])
                            ->one();
        }
        //prd($prevData);
        if(!empty($prevData)){
            $this->addError($attribute,'This '.$attribute.' has already been taken');
            return false;
        }
        return true;
    }
    
    public function sendConfirmationEmail($newUser){
        $status =   Yii::$app
            ->mailer
            ->compose(
                ['html' => 'activation-mail-html', 'text' => 'activation-mail-text'],
                ['user' => $newUser]
            )
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' robot'])
            ->setTo($newUser->email)
            ->setSubject('Steps to Activate Your Account')
            ->send();
        
        return $status;
    }
    
}
