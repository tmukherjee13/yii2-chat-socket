<?php

namespace tmukherjee13\sochat\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property integer $id
 * @property integer $to_user
 * @property integer $from_user
 * @property string $message
 * @property integer $status
 * @property string $created_at
 *
 * @property User $fromUser
 * @property User $toUser
 */
class Chat extends \yii\db\ActiveRecord
{

    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 0;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value'              => new Expression('NOW()'),
            ],
        ];
    }


     /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['to_user', 'from_user', 'message'], 'required'],
            [['to_user', 'from_user', 'status'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_ENABLED],
            [['message'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['from_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['from_user' => 'id']],
            [['to_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['to_user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'to_user'    => Yii::t('app', 'To User'),
            'from_user'  => Yii::t('app', 'From User'),
            'message'    => Yii::t('app', 'Message'),
            'status'     => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(User::className(), ['id' => 'from_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        return $this->hasOne(User::className(), ['id' => 'to_user']);
    }
}
