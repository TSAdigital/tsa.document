<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document}}`.
 */
class m221126_155912_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'date' => $this->date()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'author' => $this->integer()->notNull(),
            'resolution' => $this->json()->defaultValue(NULL),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-author-id',
            'document',
            'author'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-author-id',
            'document',
            'author',
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
            'fk-author-id',
            'document'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-author-id',
            'document'
        );

        $this->dropTable('{{%document}}');
    }
}
