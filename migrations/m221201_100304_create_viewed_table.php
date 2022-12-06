<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%viewed}}`.
 */
class m221201_100304_create_viewed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%viewed}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-viewed-document-id',
            'viewed',
            'document_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-viewed-document-id',
            'viewed',
            'document_id',
            'document',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-viewed-user-id',
            'viewed',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-viewed-user-id',
            'viewed',
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
            'fk-viewed-user-id',
            'viewed'
        );

        $this->dropIndex(
            'idx-viewed-user-id',
            'viewed'
        );

        $this->dropForeignKey(
            'fk-viewed-document-id',
            'viewed'
        );

        $this->dropIndex(
            'idx-viewed-document-id',
            'viewed'
        );

        $this->dropTable('{{%viewed}}');
    }
}
