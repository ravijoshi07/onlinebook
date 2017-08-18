<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%country}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $status
 */
class Country extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'status'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 8],
            [['name','code'], 'trim'],            
            [['name','code'], 'unique', 'filter'=>['<>','status','2']],
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
            'code' => 'Code',
            'status' => 'Status',
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
        $query = Country::find()
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
                'code' => [
                    'asc' => ['code' => SORT_ASC],
                    'desc' => ['code' => SORT_DESC],
                    'default' => SORT_ASC,
                ],  
            ]
        ]);


         // load the search form data and validate
        if (!($this->load($params))) {
            return $dataProvider;
        }
           

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);
            
        
        return $dataProvider;
    }    
    
    /*
     * Define Relationship functions
     */
    public function getstates(){
        return $this->hasMany(State::className(), ['id'=>'country_id']);
    }
}
