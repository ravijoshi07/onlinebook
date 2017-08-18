<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;
use yii\db\ActiveRecord;
/**
 * Login form
 */
class ChangePassword extends Model
{
    public $old_password;
    public $new_password;
    public $confirm_new_password;  
    public $isAdmin = false;
    public $role;
    private $_user = false;
    public $is_set_password;

    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($id, $config = [])
    {
        if (empty($id)) {
            throw new InvalidParamException('Id cannot be blank.');
        }
        $this->_user = User::findIdentity($id);
        if (!$this->_user) {
            throw new InvalidParamException('Invalid user id.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    //^[a-zA-Z0-9.]*$
    public function rules()
    {
        return [
            // username and password are both required
            [['old_password','new_password', 'confirm_new_password'], 'required','on'=>'changepassword'],            
            [['new_password', 'confirm_new_password'], 'required','on'=>'setpassword'],            
            ['old_password','validatePassword'],
            [['new_password', 'confirm_new_password'],'string','length'=>[6,32]],
            ['new_password','string', 'min' => 6, 'max' => 15, 'message'=>'Password Should be 6­ to 15 digit long.'], 
            ['confirm_new_password','compare','compareAttribute'=>'new_password'],            
        ];
    }
					
				/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'old_password' => 'Current Password',
			'new_password' => 'New Password',
			'confirm_new_password' => 'Confirm New Password'
												
        ];
    }
	
					
						
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {   
        $user = $this->_user;
        $check = Yii::$app->security->validatePassword($this->old_password,$user->password_hash);
        if(!$check){            
            $this->addError('old_password', 'Incorrect old password.');
        }
    }     

    /**
     * Update password.
     *
     * @return boolean if password was change.
     */
    public function updatePassword()
    {
        $user = $this->_user; 
        $user->setPassword($this->new_password);
        return $user->save(false);
    }    

    public function getUsername() {
        $user = $this->_user;
        return trim($user->first_name . ' ' . $user->last_name);
    }

    public function getEmail() {
        $user = $this->_user;
        return trim($user->email);
    }
}
