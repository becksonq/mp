<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users_to_post`.
 */
class m170818_051712_create_users_to_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'users_to_post', [
            'post_id' => $this->integer( 11 )->notNull()->defaultValue( 0 ),
            'user_id' => $this->integer( 11 )->notNull()->defaultValue( 0 )
        ] );

        $this->addPrimaryKey( 'users_to_post_pk', 'users_to_post', [ 'post_id', 'user_id' ] );
        $this->createIndex( 'fk_user_to_post_user_id_ref_user', 'users_to_post', 'user_id' );
        $this->addForeignKey( 'fk_user_to_post_post_id_ref_post', 'users_to_post', 'post_id', 'post', 'id' );
        $this->addForeignKey( 'fk_user_to_post_user_id_ref_user', 'users_to_post', 'user_id', 'user', 'id' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'users_to_post' );
    }
}
