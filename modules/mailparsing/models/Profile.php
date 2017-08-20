<?php

namespace app\modules\mailparsing\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $image
 * @property string $slack_nickname
 * @property string $youtrack_nickname
 * @property integer $delivery_digest_at_hour
 * @property integer $delivery_digest_at_minutes
 * @property integer $delivery_digest_at
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_digest_at_hour', 'delivery_digest_at_minutes', 'delivery_digest_at'], 'integer'],
            [['firstname', 'lastname', 'image', 'slack_nickname', 'youtrack_nickname'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'image' => 'Image',
            'slack_nickname' => 'Slack Nickname',
            'youtrack_nickname' => 'Youtrack Nickname',
            'delivery_digest_at_hour' => 'Delivery Digest At Hour',
            'delivery_digest_at_minutes' => 'Delivery Digest At Minutes',
            'delivery_digest_at' => 'Delivery Digest At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
