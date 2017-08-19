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
class Slider extends ActiveRecord
{
   public $imageFiles;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slider}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            //[['product_id'], 'required'],
            ['image', 'image', 'minWidth' => 1600,'minHeight' => 563, 'extensions' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 2],
            [['created_at','updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
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
        
        
        $query = Slider::find()->where(['status'=>1]);
                
        
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
           

        $query->where(['status'=>1])
            ->andFilterWhere(['like', 'image', $this->image]);
            
            
        
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
            'status' => 'Status',
        ];
    }
    public function beforeSave($insert) {
        
        if (parent::beforeSave($insert)) {
            $this->status=1;
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
