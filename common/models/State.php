<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%state}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $status
 */
class State extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%state}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'country_id', 'status'], 'required'],
            [['status','country_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'trim'],            
            [['name'], 'unique', 'filter'=>['<>','status','2']],
            [['name'], 'match','pattern' => '/^[A-Za-z ]+$/u','message' => '{attribute} can only contain word characters'],
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
            'country_id' => 'Country',
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
        $query = State::find()
                ->andWhere(['<>','status', '2' ])
                ->andWhere(['=','country_id', $params['country_id'] ]);
        
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
    public function getcountry(){
        return $this->hasOne(Country::className(), ['country_id'=>'id']);
    }
}
