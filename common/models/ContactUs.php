<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * Login form
 */
class ContactUs extends ActiveRecord
{
   public $imageFiles;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contactus}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['name','email','message'], 'required'],
           // [['created_at','updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
        ];
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
        
        
        $query = ContactUs::find()->where(['<>','id','2']);
                
        
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,            
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                //'id' => SORT_ASC,
            ],
            'attributes' => [   
                'name' => [
                    'asc' => ['name' => SORT_ASC, 'name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC, 'name' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'email' => [
                    'asc' => ['stream' => SORT_ASC, 'stream' => SORT_ASC],
                    'desc' => ['stream' => SORT_DESC, 'stream' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'message' => [
                    'asc' => ['stream' => SORT_ASC, 'stream' => SORT_ASC],
                    'desc' => ['stream' => SORT_DESC, 'stream' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'created_at' => [
                    'asc' => ['created_at' => SORT_ASC, 'created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC, 'created_at' => SORT_DESC],
                    'default' => SORT_ASC,
                ], 

                
            ]
        ]);


         // load the search form data and validate
        if (!($this->load($params))) {
            return $dataProvider;
        }
           

        $query->where(['<>','id','2'])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);
            ;
            
            
        
        return $dataProvider;
    }   
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            
        ];
    }
    public function beforeSave($insert) {
        
        if (parent::beforeSave($insert)) {
       // $this->status=1;
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
            $banner_name = 'banner_'.$name;
            $this->imageFiles->saveAs(Yii::getAlias('@main/uploads/banner/') . $banner_name);
            return $banner_name;
        } else {
            return '';
        }
    }
}
