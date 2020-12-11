<?php

use yii\db\Migration;

/**
 * Class m130524_201442_init
 */
class m130524_201442_init extends Migration
{
    /**
     * @return bool|void
     *
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'userId' => $this->primaryKey(),
            'firstName' => $this->string(40)->notNull()->defaultValue(''),
            'lastName' => $this->string(40)->notNull()->defaultValue(''),
            'patronymicName' => $this->string(40)->notNull()->defaultValue(''),
            'authKey' => $this->string(32)->notNull()->unique(),
            'passwordHash' => $this->string()->notNull(),
            'birthday' => $this->date()->notNull()->defaultValue('0000-00-00'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'createdAt' => $this->integer(11)->null(),
            'updatedAt' => $this->integer(11)->null(),
            'deletedAt' => $this->integer(11)->null(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $this->db->createCommand(
                "ALTER TABLE {{%users}} CHANGE `status` `status` ENUM('block','wait','active', 'delete') NOT NULL DEFAULT 'block';"
            )->execute();
        }

        $query = "INSERT INTO `users` ("
            ." `userId`,"
            ." `firstName`,"
            ." `lastName`,"
            ." `patronymicName`,"
            ." `authKey`,"
            ." `passwordHash`,"
            ." `birthday`,"
            ." `status`,"
            ." `createdAt`,"
            ." `updatedAt`"
            .") VALUES ("
            ."1,"
            ." 'Admin',"
            ." 'Administrator',"
            ." 'Adminich',"
            ." 'BY08kQZpky8m_VizDZKAubdunCfEJjhU',"
            .' \'$2y$13$LdIZMo2AQid24deagvQHAOuZTUXLVHOAnw2dJugLtei1zUIo/K1z6\','
            ." '0000-00-00',"
            ." 'active',"
            ." '1543622400',"
            ." '1543622400'"
            .");";
        $this->db->createCommand($query)->execute();

        parent::safeUp();
    }

    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');

        parent::safeDown();
    }
}
