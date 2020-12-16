<?php

namespace core\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * Конвертор Array to Object
     *
     * @param array $array
     *
     * @return \stdClass
     */
    public static function convertToObject(array $array):\stdClass
    {
        $object = new \stdClass();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = ArrayHelper::convertToObject($value);
            }

            $object->$key = $value;
        }

        return $object;
    }
}