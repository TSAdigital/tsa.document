<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discussion}}`.
 */
class m221205_095749_create_discussion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%discussion}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull(),
            'author' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-author-discussion-id',
            'discussion',
            'author'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-author-discussion-id',
            'discussion',
            'author',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-document-discussion-id',
            'discussion',
            'document_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-document-discussion-id',
            'discussion',
            'document_id',
            'document',
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
            'fk-author-discussion-id',
            'discussion'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-author-discussion-id',
            'discussion'
        );

        $this->dropForeignKey(
            'fk-document-discussion-id',
            'discussion'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-document-discussion-id',
            'discussion'
        );

        $this->dropTable('{{%discussion}}');
    }
}
