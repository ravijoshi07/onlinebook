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
class Contact extends \yii\db\ActiveRecord
{
    /**
     * Sends an email.
     *
     * @return bool whether the email was send
     */
    public function sendEmail($name,$subject,$email,$message)
    {   return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'contact-html', 'text' => 'contact-text'],
                ['email' => $email],
                ['name' => $name],
                ['message' => $message]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo(Yii::$app->params['supportEmail'])
            ->setSubject($subject)
            ->send();
    }
}
