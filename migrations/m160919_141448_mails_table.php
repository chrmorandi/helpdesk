<?php

use yii\db\Migration;

class m160919_141448_mails_table extends Migration
{
    public function up()
    {
        $this->createTable('mail', [
            'id' => $this->primaryKey()->unsigned(),
            'from' => $this->string(255)->notNull(),
            'subject' => "text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL",
            'uid' => $this->integer()->notNull()->unsigned()->unique(),
            'seen' => "ENUM('1', '0') NOT NULL DEFAULT '0'",
            'marker' => "ENUM('1', '0') NOT NULL DEFAULT '0'",
            'msgno' => $this->integer()->unsigned()->notNull(),
            'size' => $this->integer()->unsigned()->notNull(),
            'udate' => $this->integer()->unsigned()->notNull(),
            'folder_id' => $this->integer()->unsigned()->defaultValue(1),
            'message_id' => $this->string(255)->notNull(),
            'in_reply_to' => $this->string(255)->null(),
        ]);

        $this->createTable('attachment_mail', [
            'attach_id' => $this->primaryKey()->unsigned(),
            'fileName' => $this->string(255)->null(),
            'mail_uid' => $this->integer()->notNull()->unsigned(),
        ]);

        $this->createTable('text_mail', [
            'text_id' => $this->primaryKey()->unsigned(),
            'textHtml' => "longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL",
            'mail_uid' => $this->integer()->unique()->unsigned(),
        ]);

        $this->createIndex('mail_index', 'attachment_mail', 'mail_uid', false);
        $this->createIndex('message_id_index', 'mail', 'message_id', false);

        $this->addForeignKey('attach_fk',
            'attachment_mail', 'mail_uid',
            'mail', 'uid', 'CASCADE', null);

        $this->addForeignKey('text_fk',
            'text_mail', 'mail_uid',
            'mail', 'uid', 'CASCADE', null);


        $this->createTable('folder_mail', [
            'id' => $this->primaryKey()->unsigned(),
            'folder_name' => $this->string(30)->notNull(),
        ]);


        $this->createTable('hosting', [
            'id' => $this->primaryKey(),
            'hostip' => $this->string(30)->notNull(),
            'hostuser' => $this->string(30)->notNull(),
            'hostname' => $this->string(30)->notNull()
        ]);

        $this->execute("
            ALTER TABLE `hosting` 
            ADD `public_key` 
            VARBINARY(255) 
            NULL DEFAULT NULL 
            AFTER `hostip`, 
            ADD `hostpass` 
            VARBINARY(255) 
            NOT NULL 
            AFTER `public_key`, 
            ADD UNIQUE (`hostuser`)"
        );

        $this->insert('folder_mail', ['folder_name' => 'all']);
        $this->insert('folder_mail', ['folder_name' => 'important']);
        $this->insert('folder_mail', ['folder_name' => 'trash']);
        $this->insert('folder_mail', ['folder_name' => 'sent']);

    }


    public function down()
    {
        $this->dropTable('{{%mail}}');
        $this->dropTable('{{%attachment_mail}}');
        $this->dropTable('{{%text_mail}}');
        $this->dropTable('{{%hosting}}');
        $this->dropTable('{{%folder_mail}}');
        echo "drop tables";
    }

}
