<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m170818_045443_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'user', [
            'id'                   => $this->integer( 11 )->notNull() . ' AUTO_INCREMENT',
            'username'             => $this->string( 255 )->notNull()->unique(),
            'auth_key'             => $this->string( 32 )->notNull(),
            'password_hash'        => $this->string( 255 )->notNull(),
            'password_reset_token' => $this->string( 255 )->defaultValue( null )->unique(),
            'email'                => $this->string( 255 )->notNull()->unique(),
            'status'               => $this->smallInteger( 6 )->notNull()->defaultValue( 10 ),
            'created_at'           => $this->integer( 11 )->notNull(),
            'updated_at'           => $this->integer( 11 )->notNull()
        ] );

        $this->addPrimaryKey( 'user_pk', 'user', 'id' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'user' );
    }
}
