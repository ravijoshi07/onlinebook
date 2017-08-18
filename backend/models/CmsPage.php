<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%cms_page}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $unique_name
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class CmsPage extends ActiveRecord
{
    const STATUS_DELETED = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'unique_name', 'content'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['title', 'unique_name'], 'string', 'max' => 255],
            [['unique_name'], 'unique'],
            [['title'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
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
            'title' => 'Title',
            'unique_name' => 'Unique Name',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id){
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by UniqueName
     *
     * @param string $unique_name
     * @return static|null
     */
    public static function findByUniqueName($unique_name){
        return static::findOne(['unique_name' => $unique_name, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId(){
        return $this->getPrimaryKey();
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
        
        $query = CmsPage::find()->andWhere(['<>','status', '2' ]);
        
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
                'title' => [
                    'asc' => ['title' => SORT_ASC],
                    'desc' => ['title' => SORT_DESC],
                    'default' => SORT_ASC,
                ], 
                'unique_name' => [
                    'asc' => ['unique_name' => SORT_ASC],
                    'desc' => ['unique_name' => SORT_DESC],
                    'default' => SORT_ASC,
                ], 
                
                'created_at',
            ]
        ]);


         // load the search form data and validate
        if (!($this->load($params))) {
            return $dataProvider;
        }
           

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'unique_name', $this->unique_name])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);
            
        
        return $dataProvider;
    }    
    
}
