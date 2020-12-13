<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 *
 * Class AppAsset
 * @package frontend\assets
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset', // Включает основной yii.js файл который реализует механизм организации JavaScript кода в модулях
        'yii\web\JqueryAsset', // Включает jquery.js файл из jQuery Bower пакета
        'yii\bootstrap\BootstrapAsset', // Включает CSS файл из Twitter Bootstrap фреймворка
        'yii\bootstrap\BootstrapPluginAsset', // Включает JavaScript файл из Twitter Bootstrap фреймворка для поддержки Bootstrap JavaScript плагинов
        'yii\jui\JuiAsset', // Включает CSS и JavaScript файлы из jQuery UI библиотеки

    ];
}
