<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_item_child`.
 */
class m170817_203719_create_auth_item_child_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('auth_item_child', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull()
        ]);

        $this->addPrimaryKey('auth_item_child_pk', 'auth_item_child', ['parent', 'child']);
        $this->createIndex('child', 'auth_item_child', 'child' );
        $this->addForeignKey('fk_auth_item_child_1', 'auth_item_child', 'parent', 'auth_item', 'name' );
        $this->addForeignKey( 'fk_auth_item_child_2', 'auth_item_child', 'child', 'auth_item', 'name' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('auth_item_child');
    }
}
