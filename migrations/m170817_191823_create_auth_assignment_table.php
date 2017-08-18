<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_assignment`.
 */
class m170817_191823_create_auth_assignment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'auth_assignment', [
            'item_name'  => $this->string( 64 )->notNull(),
            'user_id'    => $this->string( 64 )->notNull(),
            'created_at' => $this->integer( 11 )->defaultValue( null )
        ] );

        $this->addPrimaryKey( 'item_name_pk', 'auth_assignment', [ 'item_name', 'user_id' ] );
        $this->addForeignKey( 'fk_auth_item_item_name', 'auth_assignment', 'item_name', 'auth_item', 'name' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'auth_assignment' );
    }
}
