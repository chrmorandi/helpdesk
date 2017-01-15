<?php
/**
 * Yii bootstrap file.
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var WebApplication the application instance
     */
    public static $app;
}
spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = include(__DIR__ . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container;


/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 */
abstract class BaseApplication extends yii\base\Application
{
}


/**
 * Class WebApplication
 * Include only Web application related components here
 *
 * @property app\commons\ConsoleRunner $consoleRunner
 * @property Apolon\sftp\SFtpManager $sftp
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 */
class ConsoleApplication extends yii\console\Application
{
}