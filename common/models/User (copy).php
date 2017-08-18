<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use yii\data\ActiveDataProvider;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
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
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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




    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        if(isset(Yii::$app->user->identity->u_id))
            $id = Yii::$app->user->identity->u_id;
        else
            $id = '';

        $query = User::find()
                //->andWhere(['<>','role', 1])
                ->andWhere(['<>','id', $id ]);
        
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,            
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                //'username' => SORT_DESC,
            ],
            'attributes' => [   
                'name' => [
                    'asc' => ['u_first_name' => SORT_ASC, 'u_last_name' => SORT_ASC],
                    'desc' => ['u_first_name' => SORT_DESC, 'u_last_name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],  
                'daterange' => [
                    'asc' => ['u_joining_date' => SORT_ASC],
                    'desc' => ['u_joining_date' => SORT_DESC],
                    'default' => SORT_ASC,
                ],  
                'u_email',                                         
                'u_status', 
            ]
        ]);


         // load the search form data and validate
        if (!($this->load($params))) {
            return $dataProvider;
        }
           
      
        // grid filtering conditions

        if (!is_null($this->daterange) && strpos($this->daterange, ' to ') !== false ) {
          
            $dates = explode(' to ', $this->daterange);          
            if(is_array($dates) && isset($dates[0]) && isset($dates[1])){
               //prd($dates);
                $start_date = date('Y-m-d',strtotime($dates[0]));
                $end_date = date('Y-m-d',strtotime($dates[1])); 
                             
                if(strtotime($start_date) == strtotime($end_date)){
                    $query->andFilterWhere(['=', 'DATE(u_joining_date)', $start_date]);
                }else{
                    $query->andFilterWhere(['between', 'DATE(u_joining_date)', $start_date, $end_date]);
                } 
            }
        }


        /*$query->andFilterWhere(['like', 'u_unique_id', $this->u_unique_id])
            ->andFilterWhere(['like', 'concat(u_first_name," ",u_last_name)', strtolower($this->name)])
            ->andFilterWhere(['like', 'u_email', $this->u_email])                      
            ->andFilterWhere(['=', 'u_status', $this->u_status])
            ->andFilterWhere(['=', 'u_ps_id', $this->u_ps_id])
            ->andFilterWhere(['=', 'u_department_id', $this->u_department_id]);*/
            
        
        return $dataProvider;
    }
}
