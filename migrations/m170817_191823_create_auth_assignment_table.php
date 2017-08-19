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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable( 'auth_assignment', [
            'item_name'  => $this->string( 64 )->notNull(),
            'user_id'    => $this->string( 64 )->notNull(),
            'created_at' => $this->integer( 11 )->defaultValue( null )
        ], $tableOptions );

        /*
         * */
        $this->createTable('auth_item_child', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull()
        ]);

        $this->addPrimaryKey('auth_item_child_pk', 'auth_item_child', ['parent', 'child']);
        $this->createIndex('child', 'auth_item_child', 'child' );

        /*
         * */
        $this->createTable( 'post', [
            'id'         => $this->primaryKey(11)->notNull(),
            'author_id'  => $this->integer( 11 )->notNull(),
            'project_id' => $this->integer( 11 )->notNull(),
            'created_at' => $this->integer( 11 )->notNull(),
            'updated_at' => $this->integer( 11 )->notNull(),
            'type'       => $this->string( 255 )->defaultValue( 'uncategorized' ),
            'body'       => $this->text(),
            'is_deleted' => $this->boolean()->defaultValue( 0 )
        ] );

        $this->createIndex( 'author_id', 'post', 'author_id' );
        $this->createIndex( 'project_id', 'post', 'project_id' );

        /*
         * */
        $this->createTable( 'users_to_post', [
            'post_id' => $this->integer( 11 )->notNull()->defaultValue( 0 ),
            'user_id' => $this->integer( 11 )->notNull()->defaultValue( 0 )
        ] );

        $this->addPrimaryKey( 'users_to_post_pk', 'users_to_post', [ 'post_id', 'user_id' ] );
        $this->createIndex( 'fk_user_to_post_user_id_ref_user', 'users_to_post', 'user_id' );

        /*
         * */
        $this->createTable( 'profile', [
            'user_id'                    => $this->primaryKey(11)->notNull(),
            'firstname'                  => $this->string( 255 )->defaultValue( null ),
            'lastname'                   => $this->string( 255 )->defaultValue( null ),
            'image'                      => $this->string( 255 )->defaultValue( null ),
            'slack_nickname'             => $this->string( 255 )->defaultValue( null ),
            'youtrack_nickname'          => $this->string( 255 )->defaultValue( null ),
            'delivery_digest_at_hour'    => $this->integer( 11 )->defaultValue( null ),
            'delivery_digest_at_minutes' => $this->integer( 11 )->defaultValue( null ),
            'delivery_digest_at'         => $this->integer( 11 )->defaultValue( null )
        ] );

        /*
         * */
        $this->createTable( 'project_notification', [
            'id'          => $this->primaryKey(11)->notNull(),
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

        $this->createIndex( 'project_id', 'project_notification', 'project_id' );

        /*
         * */
        $this->createTable( 'project_to_user', [
            'project_id' => $this->integer( 11 )->notNull()->defaultValue( 0 ),
            'user_id'    => $this->integer( 11 )->notNull()->defaultValue( 0 ),
            'role'       => $this->string( 255 )->notNull()->defaultValue( 'read' ),
            'subscribed' => $this->boolean()->notNull()->defaultValue( 0 )
        ] );

        $this->addPrimaryKey( 'project_to_user_pk', 'project_to_user', [ 'project_id', 'user_id' ] );
        $this->createIndex( 'fk_project_to_user_user_id_ref_user', 'project_to_user', 'user_id' );

        /*
         * */
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
        $this->createIndex( 'rule_name', 'auth_item', 'rule_name' );
        $this->createIndex( 'idx-auth_item-type', 'auth_item', 'type' );

        /*
         * */
        $this->createTable( 'user', [
            'id'                   => $this->primaryKey(11)->notNull(),
            'username'             => $this->string( 255 )->notNull()->unique(),
            'auth_key'             => $this->string( 32 )->notNull(),
            'password_hash'        => $this->string( 255 )->notNull(),
            'password_reset_token' => $this->string( 255 )->defaultValue( null )->unique(),
            'email'                => $this->string( 255 )->notNull()->unique(),
            'status'               => $this->smallInteger( 6 )->notNull()->defaultValue( 10 ),
            'created_at'           => $this->integer( 11 )->notNull(),
            'updated_at'           => $this->integer( 11 )->notNull()
        ] );

        /*
         * */
        $this->createTable( 'project', [
            'id'              => $this->primaryKey(11)->notNull(),
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

        $this->createIndex( 'project_manager', 'project', 'project_manager' );
        $this->createIndex( 'tech_lead', 'project', 'tech_lead' );
        $this->createIndex( 'process-manager', 'project', 'process_manager' );
        $this->createIndex( 'tech_dir', 'project', 'tech_dir' );
        $this->createIndex( 'reviewer', 'project', 'reviewer' );

        /*
         * Create auth_rule table
         * */
        $this->createTable( 'auth_rule', [
            'name'       => $this->string( 64 )->notNull(),
            'data'       => $this->text(),
            'created_at' => $this->integer( 11 )->defaultValue( null ),
            'updated_at' => $this->integer( 11 )->defaultValue( null )
        ] );

        $this->addPrimaryKey( 'auth_rule_pk', 'auth_rule', 'name' );

        /*
         * */
        $this->addPrimaryKey( 'item_name_pk', 'auth_assignment', [ 'item_name', 'user_id' ] );
        $this->addForeignKey( 'fk_auth_assignment', 'auth_assignment', 'item_name', 'auth_item', 'name' );

        $this->addForeignKey('fk_auth_item_child_1', 'auth_item_child', 'parent', 'auth_item', 'name' );
        $this->addForeignKey( 'fk_auth_item_child_2', 'auth_item_child', 'child', 'auth_item', 'name' );

        $this->addForeignKey( 'fk_post_1', 'post', 'author_id', 'user', 'id' );
        $this->addForeignKey( 'fk_post_2', 'post', 'project_id', 'project', 'id' );

        $this->addForeignKey( 'fk_user_to_post_post_id_ref_post', 'users_to_post', 'post_id', 'post', 'id' );
        $this->addForeignKey( 'fk_user_to_post_user_id_ref_user', 'users_to_post', 'user_id', 'user', 'id' );

        $this->addForeignKey( 'fk_profile', 'profile', 'user_id', 'user', 'id' );

        $this->addForeignKey( 'fk_project_notification', 'project_notification', 'project_id', 'project', 'id' );

        $this->addForeignKey( 'fk_project_to_user_project_id_ref_project', 'project_to_user', 'project_id', 'project', 'id' );
        $this->addForeignKey( 'fk_project_to_user_user_id_ref_user', 'project_to_user', 'user_id', 'user', 'id' );

        $this->addForeignKey( 'fk_auth_item', 'auth_item', 'rule_name', 'auth_rule', 'name' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'auth_assignment' );
        $this->dropTable('auth_item_child');
        $this->dropTable( 'post' );
        $this->dropTable( 'users_to_post' );
        $this->dropTable( 'profile' );
        $this->dropTable( 'project_notification' );
        $this->dropTable( 'project_to_user' );
        $this->dropTable( 'auth_item' );
        $this->dropTable( 'user' );
        $this->dropTable( 'project' );
        $this->dropTable( 'auth_rule' );

        // TODO: make dropForeignKey
    }
}
