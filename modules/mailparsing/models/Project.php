<?php

namespace app\modules\mailparsing\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $project_manager
 * @property integer $tech_lead
 * @property integer $process_manager
 * @property integer $tech_dir
 * @property integer $reviewer
 * @property integer $warning_problem
 * @property integer $warning_alarm
 * @property string $slack_name
 * @property string $emoji
 *
 * @property Post[] $posts
 * @property ProjectNotification[] $projectNotifications
 * @property ProjectToUser[] $projectToUsers
 * @property User[] $users
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'project_manager', 'tech_lead', 'process_manager', 'tech_dir', 'reviewer', 'warning_problem', 'warning_alarm'], 'integer'],
            [['name', 'slack_name', 'emoji'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'project_manager' => 'Project Manager',
            'tech_lead' => 'Tech Lead',
            'process_manager' => 'Process Manager',
            'tech_dir' => 'Tech Dir',
            'reviewer' => 'Reviewer',
            'warning_problem' => 'Warning Problem',
            'warning_alarm' => 'Warning Alarm',
            'slack_name' => 'Slack Name',
            'emoji' => 'Emoji',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectNotifications()
    {
        return $this->hasMany(ProjectNotification::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectToUsers()
    {
        return $this->hasMany(ProjectToUser::className(), ['project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('project_to_user', ['project_id' => 'id']);
    }
}
