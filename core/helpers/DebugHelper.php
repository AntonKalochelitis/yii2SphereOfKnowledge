<?php

namespace core\helpers;

/**
 * Class DebugHelper
 *
 *
 * @package core\helpers
 */
class DebugHelper
{
    public static function d($data, bool $exit = false)
    {
        if (YII_ENV_DEV) {
            if (!empty($data)) {
                echo '<pre>';
                print_r($data);
                echo '</pre>';

                $trace = debug_backtrace();

                echo 'trace<pre>';
                print_r([
                    'file' => $trace[0]['file'],
                    'line' => $trace[0]['line'],
                    'class' => $trace[0]['class']
                ]);
                echo '</pre>';
            } else {
                $trace = debug_backtrace();
                echo "trace:[NODATA]<pre>";
                echo var_dump($data);
                print_r([
                    'file' => $trace[0]['file'],
                    'line' => $trace[0]['line'],
                    'class' => $trace[0]['class']
                ]);
                echo '</pre>';
            }

            if (true == $exit) {
                exit();
            }
        }
    }

    /**
     * Записываем лог в файл
     *
     * @param $data
     * @param string $nameFile
     *
     * @return void
     */
    public static function dToFile($data, string $nameFile = 'Default')
    {
        if (YII_ENV_DEV) {
            $nameFile = \Yii::getAlias('@runtime').DIRECTORY_SEPARATOR.trim($nameFile).'-'.time().'-Debug.log';
            $text = '';

            if (!empty($data)) {
                $text   .= "<pre>";
                $text   .= print_r($data, true);
                $text   .= "</pre>";
                $trace  = debug_backtrace();

                $text   .= 'trace:<pre>';
                $text   .= print_r([
                    'file' => $trace[0]['file'],
                    'line' => $trace[0]['line'],
                    'class' => $trace[0]['class']
                ], true);
                $text   .= '</pre>';
            } else {
                $text   .= "<pre>";
                $text   .= print_r($data, true);
                $text   .= "</pre>";
            }

            file_put_contents($nameFile, $text, FILE_APPEND);
        }
    }
}