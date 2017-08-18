<?php

namespace common\components;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class CommonController extends Controller {
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    public function _bringModelErrorsForAPI($modelErrors=null){
        $errHere = [];
        foreach ($modelErrors as $k=>$valArr){
            $errHere[$k]    =   implode("\n",$valArr);
        }
        return $errHere;
    }
} 
