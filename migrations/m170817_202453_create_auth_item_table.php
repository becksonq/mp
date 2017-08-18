<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_item`.
 */
class m170817_202453_create_auth_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'auth_item', [
            'name'        => $this->string( 64 )->notNull(),
            'type'        => $this->integer( 11 )->notNull(),
            'description' => $this->text(),
            'rule_name'   => $this->string( 64 )->defaultValue( null ),
            'data'        => $this->text(),
            'created_at'  => $this->integer( 11 )->defaultValue( null ),
            'updated_at'  => $this->integer( 11 )->defaultValue( null )
        ] );

        $this->addPrimaryKey( 'name_pk', 'auth_item', 'name' );
        $this->addForeignKey( 'fk_auth_item', 'auth_item', 'rule_name', 'auth_rule', 'name' );
        $this->createIndex( 'rule_name', 'auth_item', 'rule_name' );
        $this->createIndex( 'idx-auth_item-type', 'auth_item', 'type' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'auth_item' );
    }
}
