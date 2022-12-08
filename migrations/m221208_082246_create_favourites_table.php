<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favourites}}`.
 */
class m221208_082246_create_favourites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favourites}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull()->unique(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-favourites-document-id',
            'favourites',
            'document_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-favourites-document-id',
            'favourites',
            'document_id',
            'document',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-favourites-user-id',
            'favourites',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-favourites-user-id',
            'favourites',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-favourites-user-id',
            'favourites'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-favourites-user-id',
            'favourites'
        );

        $this->dropForeignKey(
            'fk-favourites-document-id',
            'favourites'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-favourites-document-id',
            'favourites'
        );

        $this->dropTable('{{%favourites}}');
    }
}
