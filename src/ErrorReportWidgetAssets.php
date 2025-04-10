<?php

/**
 * @copyright Copyright &copy; Sfiligoi Federico
 * @package Js Error Mailer
 * @version 1.0.0
 */

namespace sfili81\ErrorReportWidget;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Asset bundle for [[Growl]] widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class ErrorReportWidgetAssets extends AssetBundle
{
    public $sourcePath = __DIR__;
    
    public $css = [];
    public $js = [
        ['assets/errorHandler.js', 'position' => View::POS_HEAD],
    ];
     public $publishOptions = [
        'forceCopy' => true,
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}