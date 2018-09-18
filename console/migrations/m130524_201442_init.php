<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(40)->notNull()->defaultValue(''),
            'last_name' => $this->string(40)->notNull()->defaultValue(''),
            'patronymic_name' => $this->string(40)->notNull()->defaultValue(''),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'reset_token' => $this->string()->unique(),
            'email' => $this->string(80)->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10)
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $this->db->createCommand("ALTER TABLE `users` CHANGE `status` `status` ENUM('0','5','10') NOT NULL DEFAULT '10';")->execute();
            $this->db->createCommand("ALTER TABLE `users` ADD `created_at` TIMESTAMP NOT NULL")->execute();
            $this->db->createCommand("ALTER TABLE `users` CHANGE `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")->execute();
            $this->db->createCommand("ALTER TABLE `users` ADD `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")->execute();
        }
        parent::safeUp();
    }

    public function safeDown()
    {
        $this->dropTable('{{%users}}');
        parent::safeDown();
    }
}
