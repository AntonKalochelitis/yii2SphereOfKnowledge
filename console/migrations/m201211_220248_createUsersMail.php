<?php

use yii\db\Migration;

/**
 * Class m201211_220248_createUsersMail
 */
class m201211_220248_createUsersMail extends Migration
{
    private $table = 'usersMail';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%'.$this->table.'}}', [
            'userMailId' => $this->primaryKey(),
            'userId' => $this->integer(11)->Null(),
            'identifier' => $this->string(80)->Null(),
            'description' => $this->text()->notNull(),
            'token' => $this->string(120)->unique(),
            'verification' => $this->boolean()->defaultValue('0'),
            'status' => $this->boolean()->defaultValue('0'),
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-'.$this->table.'-'.'users'.'-userId}}',
            '{{%'.$this->table.'}}',
            'userId',
            '{{%users}}',
            'userId',
            'CASCADE',
            'CASCADE'
        );

        if ($this->db->driverName === 'mysql') {
            $query = "INSERT INTO {{%".$this->table."}}"
                ." (`userMailId`, `userId`, `identifier`, `description`, `token`, `verification`, `status`)".
                " VALUES"
                ." (1, 1, 'developing.w@gmail.com', '', NULL, 1, 1);";
            $this->db->createCommand($query)->execute();
        }

        $this->createIndex(
            '{{%index-'.$this->table.'-verification}}',
            '{{%'.$this->table.'}}',
            ['verification']
        );

        $this->createIndex(
            '{{%index-'.$this->table.'-status}}',
            '{{%'.$this->table.'}}',
            ['status']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%'.$this->table.'}}');

        parent::safeDown();
    }
}
