<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%celebrity}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property integer $product_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class Celebrity extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    public $imageFiles;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%celebrity}}';
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['name','image','description','product_id'];        
        return $scenarios;
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'product_id'], 'required'],            
            [['password_hash', 'confirm_password'], 'string', 'min'=>6, 'skipOnEmpty'=>true, 'on'=>'update'],
            
            ['image', 'image', 'minWidth' => 50,'minHeight' => 50, 'extensions' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 2],
            
            [['description'], 'string'],
            [['name'], 'unique', 'filter'=>['<>','status','2']],
            [['product_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['created_at','updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'description' => 'Description',
            'product_id' => 'Product ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
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
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Celebrity::find()
                ->andWhere(['<>','status', '2' ]);
        
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
                'name' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_ASC,
                ], 
                'description' => [
                    'asc' => ['description' => SORT_ASC],
                    'desc' => ['description' => SORT_DESC],
                    'default' => SORT_ASC,
                ], 
                'created_at' => [
                    'asc' => ['created_at' => SORT_ASC],
                    'desc' => ['created_at' => SORT_DESC],
                    'default' => SORT_ASC,
                ],  
            ]
        ]);


         // load the search form data and validate
        if (!($this->load($params))) {
            return $dataProvider;
        }
           

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);
            
        
        return $dataProvider;
    }  
    
    public function upload()
    {
        if ($this->validate()) { 
            $name = md5(uniqid(rand(), true)) . '.' . $this->imageFiles->extension;
            $celeb_name = 'celeb_'.$name;
            $this->imageFiles->saveAs(Yii::getAlias('@main/uploads/celebrity/') . $celeb_name);
            return $celeb_name;
        } else {
            return '';
        }
    }
    
}
