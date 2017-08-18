<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 */
class m170817_210739_create_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable( 'post', [
            'id'         => $this->integer( 11 )->notNull() . ' AUTO_INCREMENT',
            'author_id'  => $this->integer( 11 )->notNull(),
            'project_id' => $this->integer( 11 )->notNull(),
            'created_at' => $this->integer( 11 )->notNull(),
            'updated_at' => $this->integer( 11 )->notNull(),
            'type'       => $this->string( 255 )->defaultValue( 'uncategorized' ),
            'body'       => $this->text(),
            'is_deleted' => $this->boolean()->defaultValue( 0 )
        ] );

        $this->addPrimaryKey( 'post_pk', 'post', 'id' );
        $this->createIndex( 'author_id', 'post', 'author_id' );
        $this->createIndex( 'project_id', 'post', 'project_id' );

        $this->addForeignKey( 'fk_post_1', 'post', 'author_id', 'user', 'id' );
        $this->addForeignKey( 'fk_post_2', 'post', 'project_id', 'project', 'id' );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable( 'post' );
    }
}
