<?php

namespace app\commons;

use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\FileHelper as File;

/**
 * Class ConsoleRunner
 *
 * This is property the params.
 * @property string $logFile - log file
 * @property string $run - running command
 * @property string $STDOUT - STDOUT
 * @property string $command - full CMD command
 * @property string $STDERR - error file
 * @property string $dir - path log file
 * @property string $controller - controller command
 * @property string $process - action and params command
 */
class ConsoleRunner extends Component
{

    /**
     * @var string Console application file that will be executed.
     * Usually it can be `yii` file.
     */
    public $yiiFile;

    /**
     * @var string PHP executable including full path
     * Needed because PHP_BINDIR and PHP_BINARY do not work properly under Windows
     */
    public $php;

    /**
     * @var string runtimeFolder
     */
    public $runtimeFolder;

    /**
     * @var string Command
     */
    public $cmd;

    /**
     * @var array Params
     */
    protected $params;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->yiiFile === null) {
            throw new InvalidConfigException('The "yiiFile" property must be set.');
        }

        if ($this->runtimeFolder === null) {
            throw new InvalidConfigException('The "runtimeFolder" property must be set.');
        }
        $this->yiiFile = Yii::getAlias('@app') . $this->yiiFile;
    }

    /**
     * @param $cmd
     * @return array|bool|mixed
     */
    public function restart($cmd)
    {
        $this->killProcess($this->getPidCommand($cmd)['ProcessId']);
        return $this->run($cmd);
    }

    /**
     * @param $cmd
     * @return mixed
     */
    public function stop($cmd)
    {
        return $this->killProcess($this->getPidCommand($cmd)['ProcessId']);
    }


    /**
     * @param string $cmd
     * @return array|bool|mixed
     */
    public function run(string $cmd)
    {
        if (!empty($cmd)) {
            $this->cmd = $cmd;
            if ($this->isWindows() === true) {
                return $this->runOnWindows();

            }
        }
        return false;
    }


    /**
     * Check operating system
     *
     * @return boolean true if it's Windows OS
     */
    public function isWindows()
    {
        if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array|bool|mixed
     * @throws ErrorException
     */
    protected function runOnWindows()
    {

        if ($this->parserParams()) {
            $this->createLogFile();
        } else throw new ErrorException("Invalid command");

        if ($this->logFile && is_file($this->logFile)) {
            $log = $this->getProccesFile($this->logFile);
            if (!empty($log) && $this->isRunning($log['ProcessId']))
                return $this;
        }

        $descriptorspec = array(
            1 => array("file", $this->STDOUT, 'w+'),
            2 => array("file", $this->STDERR, "w+")
        );

        $handle = proc_open('start /B ' . $this->command, $descriptorspec, $pipes);

        if (is_resource($handle) && ($pid = $this->getPidCommand($this->run))) {
            $log = array_merge($this->params, $pid);
            $this->writeProccesLog($log);
            proc_close($handle);
        }

        return $this;
    }

    public function getError()
    {
        return $this->getProccesFile($this->STDERR);
    }

    public function getOut()
    {
        return $this->getProccesFile($this->STDOUT, false);
    }

    public function getLog()
    {
        return (object)$this->getProccesFile($this->logFile);
    }

    protected function parserParams()
    {
        list($controller, $command) = explode('/', $this->cmd);
        if ($controller && $command) {
            $this->run = $this->cmd;
            $this->controller = $controller;
            $this->process = $controller . md5($command);
            $this->command = $this->php . ' ' . Yii::getAlias($this->yiiFile) . ' ' . $this->cmd;
            return true;
        }
        return false;
    }

    protected function createLogFile()
    {
        $runtime = Yii::getAlias('@runtime');

        $this->dir = $runtime . $this->runtimeFolder . "/" . $this->controller;
        $this->logFile = $this->dir . '/' . $this->process . '-info.txt';
        $this->STDOUT = $this->dir . '/' . $this->process . "-out.txt";
        $this->STDERR = $this->dir . '/' . $this->process . "-error.txt";

        if ($this->logFile && $this->dir) {
            if (!is_dir($this->dir))
                File::createDirectory($this->dir, 0777);

            if (!is_file($this->logFile)) {
                $file = fopen($this->logFile, 'w+');
                fclose($file);
            }
        }

    }


    /**
     * @param $file
     * @param bool $json
     * @return bool|mixed|string
     */
    public function getProccesFile($file, $json = true)
    {
        $content = file_get_contents($file);
        return ($json) ? Json::decode($content) : $content ?? false;
    }


    /**
     * @param $pid
     * @return bool
     */
    public function isRunning($pid)
    {
        return (stripos($this->shell("tasklist /fi \"PID eq $pid\""), 'php.exe')) ? true : false;

    }

    /**
     * @param $command
     * @return array|bool
     */
    public function getPidCommand($command)
    {
        $list = $this->shell('
        wmic process 
        where "CommandLine  like \'%' . $command . '%\' 
        and name=\'php.exe\'" 
        list BRIEF 
        /FORMAT: list');
        $values = explode("\n", trim($list));
        $info = [];
        foreach ($values as $value) {
            list($type, $var) = explode('=', $value);
            $info[$type] = $var;
        }
        return array_diff($info, array('', NULL, false)) ?? false;
    }

    /**
     * @param $log
     */
    protected function writeProccesLog(array $log)
    {
        if (!empty($log['logFile']))
            file_put_contents($log['logFile'], Json::encode($log));
    }

    public function __get($name)
    {
        if ($this->params[$name])
            return $this->params[$name];
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (!empty($value))
            $this->params[$name] = $value;
        else parent::__set($name, $value);
    }

    /**
     * @param $pid
     * @return mixed
     */
    public function killProcess($pid)
    {
        return $this->shell("taskkill /F /PID $pid") ?? 'process not found';
    }


    /**
     * @param $command
     * @return string
     */
    public function shell(string $command)
    {
        return iconv('CP866', 'UTF-8',
            shell_exec(
                escapeshellcmd(str_replace("\n", false,
                    $command))));
    }

}