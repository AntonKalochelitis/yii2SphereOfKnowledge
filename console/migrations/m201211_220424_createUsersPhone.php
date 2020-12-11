<?php

use yii\db\Migration;

/**
 * Class m201211_220424_createUsersPhone
 */
class m201211_220424_createUsersPhone extends Migration
{
    private $table = 'usersPhone';

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
            'userPhoneId' => $this->primaryKey(),
            'userId' => $this->integer(11)->Null(),
            'identifier' => $this->bigInteger(80)->Null(),
            'token' => $this->integer(6)->unique(),
            'description' => $this->text()->notNull(),
            'verification' => $this->boolean()->defaultValue('0'),
            'status' => $this->boolean()->defaultValue('0'),
        ], $tableOptions);

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

        $this->addForeignKey(
            '{{%fk-'.$this->table.'-'.'users'.'-userId}}',
            '{{%'.$this->table.'}}',
            'userId',
            '{{%'.'users'.'}}',
            'userId',
            'CASCADE',
            'CASCADE'
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
