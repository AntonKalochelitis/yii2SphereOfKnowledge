<?php

namespace core\abstracts\db;

/**
 * Class AbstractRepository
 * @package core\abstracts
 */
abstract class AbstractRepository extends \yii\db\ActiveRecord
{
    protected const TABLE_NAME = '';
    protected const DB_NAME = '';

    /**
     * AbstractRepository constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%' . static::TABLE_NAME . '}}';
    }

    /**
     * @return null|object|\yii\db\Connection
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        if (!empty(static::DB_NAME)) {
            return \Yii::$app->get(static::DB_NAME);
        }

        return parent::getDb();
    }
}