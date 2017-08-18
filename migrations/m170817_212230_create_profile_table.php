<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile`.
 */
class m170817_212230_create_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'profile', [
            'user_id'                    => $this->integer( 11 )->notNull() . ' AUTO_INCREMENT',
            'firstname'                  => $this->string( 255 )->defaultValue( null ),
            'lastname'                   => $this->string( 255 )->defaultValue( null ),
            'image'                      => $this->string( 255 )->defaultValue( null ),
            'slack_nickname'             => $this->string( 255 )->defaultValue( null ),
            'youtrack_nickname'          => $this->string( 255 )->defaultValue( null ),
            'delivery_digest_at_hour'    => $this->integer( 11 )->defaultValue( null ),
            'delivery_digest_at_minutes' => $this->integer( 11 )->defaultValue( null ),
            'delivery_digest_at'         => $this->integer( 11 )->defaultValue( null )
        ] );

        $this->addPrimaryKey( 'profile_pk', 'profile', 'user_id' );
        $this->addForeignKey( 'fk_profile', 'profile', 'user_id', 'user', 'id' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'profile' );
    }
}
