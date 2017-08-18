<?php

use yii\db\Migration;

/**
 * Handles the creation of table `auth_rule`.
 */
class m170817_204307_create_auth_rule_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'auth_rule', [
            'name'       => $this->string( 64 )->notNull(),
            'data'       => $this->text(),
            'created_at' => $this->integer( 11 )->defaultValue( null ),
            'updated_at' => $this->integer( 11 )->defaultValue( null )
        ] );

        $this->addPrimaryKey( 'auth_rule_pk', 'auth_rule', 'name' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'auth_rule' );
    }
}
