<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%subcategory}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class Subcategory extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subcategory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'status'], 'required'],
            [['status', 'category_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'trim'],            
            [['name'], 'unique', 'filter'=>['<>','status','2']],
            [['name'], 'match','pattern' => '/^[A-Za-z ]+$/u','message' => '{attribute} can only contain word characters'],
            [['created_at','updated_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            //['status', 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_DELETED]],
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
            'category_id' => 'Category',
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
        $query = Subcategory::find()
                ->andWhere(['<>','status', '2' ])
                ->andWhere(['=','category_id', $params['category_id'] ]);
        
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
            ->andFilterWhere(['like', 'created_at', $this->created_at]);
            
        
        return $dataProvider;
    }    

    /*
     * Define Relationship functions
     */
    public function getcategory(){
        return $this->hasOne(Category::className(), ['category_id'=>'id']);
    }
    
}
