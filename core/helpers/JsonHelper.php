<?php

namespace core\helpers;

/**
 * Class JsonHelper
 * @package core\helpers
 */
class JsonHelper extends \yii\helpers\Json
{
    /**
     * Валидация строки
     *
     * JSON_ERROR_NAME              Ошибок нет
     * JSON_ERROR_DEPTH             Достигнута максимальная глубина стека
     * JSON_ERROR_STATE_MISMATCH    Неверный или не корректный JSON
     * JSON_ERROR_CTRL_CHAR         Ошибка управляющего символа, возможно неверная кодировка
     * JSON_ERROR_SYNTAX            Синтаксическая ошибка
     * JSON_ERROR_UTF8              Некорректные символы UTF-8, возможно неверная кодировка PHP 5.3.3
     * JSON_ERROR_RECURSION         Одна или несколько зацикленных ссылок в кодируемом значении PHP 5.5.0
     * JSON_ERROR_INF_OR_NAN        Одно или несколько значений NAN или INF в кодируемом значении PHP 5.5.0
     * JSON_ERROR_UNSUPPORTED_TYPE  Передано значение с неподдерживаемым типом PHP 5.5.0
     *
     * @param mixed $jsonLine
     *
     * @return boolean
     */
    public static function isJson($jsonLine = ''):bool
    {
        // Тут необходимо использовать стандартный класс php
        // Если используем Yii2 Helper он возвращает ошибку.
        $jsonDecode = json_decode($jsonLine);

        if (JSON_ERROR_NONE != json_last_error()) {
            return false;
        }

        return true;
    }
}