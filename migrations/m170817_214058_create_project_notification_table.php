<?php

use yii\db\Migration;

/**
 * Handles the creation of table `project_notification`.
 */
class m170817_214058_create_project_notification_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'project_notification', [
            'id'          => $this->integer( 11 )->notNull() . ' AUTO_INCREMENT',
            'project_id'  => $this->integer( 11 )->defaultValue( null ),
            'role'        => $this->string( 255 )->defaultValue( null ),
            'message'     => $this->string( 1024 )->defaultValue( null ),
            'day_1'       => $this->boolean()->defaultValue( 0 ),
            'day_2'       => $this->boolean()->defaultValue( 0 ),
            'day_3'       => $this->boolean()->defaultValue( 0 ),
            'day_4'       => $this->boolean()->defaultValue( 0 ),
            'day_5'       => $this->boolean()->defaultValue( 0 ),
            'day_6'       => $this->boolean()->defaultValue( 0 ),
            'day_7'       => $this->boolean()->defaultValue( 0 ),
            'hour'        => $this->integer( 11 )->defaultValue( null ),
            'minute'      => $this->integer( 11 )->defaultValue( null ),
            'time'        => $this->string( 255 )->defaultValue( null ),
            'created_at'  => $this->integer( 11 )->defaultValue( null ),
            'updated_at'  => $this->integer( 11 )->defaultValue( null ),
            'delivery_at' => $this->integer( 11 )->defaultValue( null )
        ] );

        $this->addPrimaryKey( 'project_notification_pk', 'project_notification', 'id' );
        $this->createIndex( 'project_id', 'project_notification', 'project_id' );
        $this->addForeignKey( 'fk_project_notification', 'project_notification', 'project_id', 'project', 'id' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'project_notification' );
    }
}
