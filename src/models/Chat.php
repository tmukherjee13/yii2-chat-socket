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
 * @property integer $receiver
 * @property integer $sender
 * @property string $message
 * @property integer $status
 * @property string $created_at
 *
 * @property User $sender
 * @property User $receiver
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
            [['receiver', 'sender', 'message'], 'required'],
            [['receiver', 'sender', 'status'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_ENABLED],
            [['message'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['sender'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender' => 'id']],
            [['receiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'receiver'    => Yii::t('app', 'To User'),
            'sender'  => Yii::t('app', 'From User'),
            'message'    => Yii::t('app', 'Message'),
            'status'     => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver']);
    }
}
