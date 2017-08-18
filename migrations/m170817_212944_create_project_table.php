<?php

use yii\db\Migration;

/**
 * Handles the creation of table `project`.
 */
class m170817_212944_create_project_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'project', [
            'id'              => $this->integer( 11 )->notNull() . ' AUTO_INCREMENT',
            'name'            => $this->string( 255 )->notNull(),
            'status'          => $this->integer( 11 )->notNull()->defaultValue( 10 ),
            'project_manager' => $this->integer( 11 )->defaultValue( null ),
            'tech_lead'       => $this->integer( 11 )->defaultValue( null ),
            'process_manager' => $this->integer( 11 )->defaultValue( null ),
            'tech_dir'        => $this->integer( 11 )->defaultValue( null ),
            'reviewer'        => $this->integer( 11 )->defaultValue( null ),
            'warning_problem' => $this->integer( 11 )->defaultValue( 0 ),
            'warning_alarm'   => $this->integer( 11 )->defaultValue( 0 ),
            'slack_name'      => $this->string( 255 )->defaultValue( null ),
            'emoji'           => $this->string( 255 )->defaultValue( null )
        ] );

        $this->addPrimaryKey( 'project_pk', 'project', 'id' );
        $this->createIndex( 'project_manager', 'project', 'project_manager' );
        $this->createIndex( 'tech_lead', 'project', 'tech_lead' );
        $this->createIndex( 'process-manager', 'project', 'process_manager' );
        $this->createIndex( 'tech_dir', 'project', 'tech_dir' );
        $this->createIndex( 'reviewer', 'project', 'reviewer' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'project' );
    }
}
