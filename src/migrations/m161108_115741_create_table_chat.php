<?php

use yii\db\Migration;

/**
 * Handles the creation for table `{{%chat}}`.
 */
class m161108_115741_create_table_chat extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%chat}}', [

            'id' => $this->primaryKey()->unsigned()->notNull(),
            'to_user' => $this->integer(11)->unsigned()->notNull(),
            'from_user' => $this->integer(11)->unsigned()->notNull(),
            'message' => $this->text()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment("0 => Deleted, 1 => Active"),
            'created_at' => $this->datetime()->notNull(),

        ]);
 
        // creates index for column `from_user`
        $this->createIndex(
            'fk-chat-from_user-user-id',
            '{{%chat}}',
            'from_user'
        );

        // add foreign key for table `wf_user`
        $this->addForeignKey(
            'fk-chat-from_user-user-id',
            '{{%chat}}',
            'from_user',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_user`
        $this->createIndex(
            'fk-chat-to_user-user-id',
            '{{%chat}}',
            'to_user'
        );

        // add foreign key for table `wf_user`
        $this->addForeignKey(
            'fk-chat-to_user-user-id',
            '{{%chat}}',
            'to_user',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `wf_user`
        $this->dropForeignKey(
            'fk-chat-from_user-user-id',
            '{{%chat}}'
        );

        // drops index for column `from_user`
        $this->dropIndex(
            'fk-chat-from_user-user-id',
            '{{%chat}}'
        );

        // drops foreign key for table `wf_user`
        $this->dropForeignKey(
            'fk-chat-to_user-user-id',
            '{{%chat}}'
        );

        // drops index for column `to_user`
        $this->dropIndex(
            'fk-chat-to_user-user-id',
            '{{%chat}}'
        );

        $this->dropTable('{{%chat}}');
    }
}
