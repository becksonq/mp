<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "project_notification".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $role
 * @property string $message
 * @property integer $day_1
 * @property integer $day_2
 * @property integer $day_3
 * @property integer $day_4
 * @property integer $day_5
 * @property integer $day_6
 * @property integer $day_7
 * @property integer $hour
 * @property integer $minute
 * @property string $time
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $delivery_at
 *
 * @property Project $project
 */
class ProjectNotification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'day_1', 'day_2', 'day_3', 'day_4', 'day_5', 'day_6', 'day_7', 'hour', 'minute', 'created_at', 'updated_at', 'delivery_at'], 'integer'],
            [['role', 'time'], 'string', 'max' => 255],
            [['message'], 'string', 'max' => 1024],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'role' => 'Role',
            'message' => 'Message',
            'day_1' => 'Day 1',
            'day_2' => 'Day 2',
            'day_3' => 'Day 3',
            'day_4' => 'Day 4',
            'day_5' => 'Day 5',
            'day_6' => 'Day 6',
            'day_7' => 'Day 7',
            'hour' => 'Hour',
            'minute' => 'Minute',
            'time' => 'Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'delivery_at' => 'Delivery At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }
}
