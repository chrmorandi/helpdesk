<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/dropzone.min.css',
        'css/jquery-ui.css',
        'css/jquery-labelauty.css',
        'css/nprogress.css'
    ];
    public $js = [
        'js/lib.js',
        'js/vendor/nprogress.js',
        'js/vendor/jquery-labelauty.js',
        'js/vendor/dropzone.min.js',
        'js/vendor/underscore-min.js',
        'js/vendor/autobahn.min.js',
        'js/vendor/backbone.js',
        'js/vendor/jquery-ui.js',
        'js/models/MailModel.js',
        'js/models/NotificationModel.js',
        'js/models/ConsoleModel.js',
        'js/models/SftpClientModel.js',
        'js/views/MailView.js',
        'js/views/NotificationView.js',
        'js/views/SftpClientView.js',
        'js/views/ReplyFormView.js',
        'js/views/ConsoleView.js',
        'js/views/MailManagerView.js',
        'js/router.js',
        'js/app.js',
        'js/socket.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        
    ];
}
