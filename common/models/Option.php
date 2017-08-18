<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $unit
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class Option extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'unit', 'status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['unit'], 'string', 'max' => 20],
            [['name','unit'], 'trim'],            
            //[['name'], 'unique', 'filter'=>['<>','status','2']],
            [['name'], 'customUnique'],
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
            'unit' => 'Unit',
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
    
    public function search($params)
    {
        $query = Option::find()
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
                'unit' => [
                    'asc' => ['unit' => SORT_ASC],
                    'desc' => ['unit' => SORT_DESC],
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
                ->andFilterWhere(['like', 'unit', $this->unit])
                ->andFilterWhere(['like', 'created_at', $this->created_at]);
            
        
        return $dataProvider;
    }
    
    public function customUnique(){
        if($this->isNewRecord){
            $prevData   =   Option::find()->where(['name'=>$this->name,'unit'=>$this->unit])
                            ->andWhere(['<>','status','2'])
                            ->one();
        } else {
            $prevData   =   Option::find()->where(['name'=>$this->name,'unit'=>$this->unit])
                            ->andWhere(['<>','status','2'])
                            ->andWhere(['<>','id',$this->id])
                            ->one();
        }
        //prd($prevData);
        if(!empty($prevData)){
            $this->addError('name','This name with unit has already been taken');
            return false;
        }
        return true;
    }
    
}
