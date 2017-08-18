<?php

use yii\db\Migration;

/**
 * Handles the creation of table `project_to_user`.
 */
class m170818_044716_create_project_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'project_to_user', [
            'project_id' => $this->integer( 11 )->notNull()->defaultValue( 0 ),
            'user_id'    => $this->integer( 11 )->notNull()->defaultValue( 0 ),
            'role'       => $this->string( 255 )->notNull()->defaultValue( 'read' ),
            'subscribed' => $this->boolean()->notNull()->defaultValue( 0 )
        ] );

        $this->addPrimaryKey( 'project_to_user_pk', 'project_to_user', [ 'project_id', 'user_id' ] );
        $this->createIndex( 'fk_project_to_user_user_id_ref_user', 'project_to_user', 'user_id' );
        $this->addForeignKey( 'fk_project_to_user_project_id_ref_project', 'project_to_user', 'project_id', 'project',
            'id' );
        $this->addForeignKey( 'fk_project_to_user_user_id_ref_user', 'project_to_user', 'user_id', 'user', 'id' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'project_to_user' );
    }
}
