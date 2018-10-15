<?php

namespace core\abstracts;

use Yii;
use yii\db\ActiveRecord;

abstract class AbstractRepository extends ActiveRecord
{
    protected const TABLE_NAME = '';
    protected const DB_NAME = '';

    protected $_service = null;

    public function __construct(array $config = [])
    {
        $this->_service = $this->initService();

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%' . static::TABLE_NAME . '}}';
    }

    public static function getDb()
    {
        if (!empty(static::DB_NAME)) {
            return Yii::$app->get(static::DB_NAME);
        }
        return parent::getDb();
    }

    /**
     * @return Service
     */
    abstract protected function initService();

    public function getService()
    {
        return $this->_service;
    }
}