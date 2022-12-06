<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%upload}}`.
 */
class m221129_071433_create_upload_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%upload}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'dir' => $this->string(255)->notNull(),
            'file_name' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-document-id',
            'upload',
            'document_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-document-id',
            'upload',
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
            'fk-document-id',
            'upload'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-document-id',
            'upload'
        );

        $this->dropTable('{{%upload}}');
    }
}
