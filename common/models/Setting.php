<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * Setting model
 *
 * @property integer $id
 * @property string $site_title
 * @property string $admin_email
 * @property string $invester_guide
 * @property string $front_per_page_record
 * @property integer $paypal_signature
 * @property integer $paypal_password
 */
class Setting extends ActiveRecord {
    /**
     * @inheritdoc
     */
    public $imageFiles;
    public $Site_Name;
    public $Admin_Email;
    public $Front_Per_Page_Record;
    public static function tableName()
    {
        return '{{%websetting}}';
    }
   
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

        /**
     * @inheritdoc
     */
    public function rules()
    {   

        return [
            [['Site_Name', 'Admin_Email','Front_Per_Page_Record'], 'required'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'site_title' => 'Site Title',
            'admin_email' => 'Admin Email',
            'invester_guide' => 'Investor Guide',
            'paypal_signature' => 'Paypal Signature',
            'paypal_password' => 'Paypal Password',
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) { 
            $name = md5(uniqid(rand(), true)) . '.' . $this->imageFiles->extension;
            $admin_name = 'investor_'.$name;
            $this->imageFiles->saveAs(Yii::getAlias('@main/uploads/admin/') . $admin_name);
            return $admin_name;
        } else {
            return '';
        }
    }
    
}
