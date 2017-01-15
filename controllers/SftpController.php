<?php

namespace app\controllers;


use Apolon\sftp\SFtpManager;
use app\commons\Security;
use app\models\Hosting;
use yii\web\Controller;
use Yii;
use yii\base\Exception;
use yii\helpers\Json;

/**
 * Class SftpController
 * @package app\controllers
 */
class SftpController extends AppController
{
    use Security;

    /**
     * @var SFtpManager $sftp
     */
    public $sftp;


    /**
     * @var object $host
     */
    public $host;


    /**
     * @var array $host
     */
    public $hostList;


    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if ($this->request->isAjax) {
            $this->sftp = Yii::$app->sftp;
            $hostId = $this->request->get('hostId');
            if (!empty($hostId)) {
                $this->host = Hosting::findOne(['id' => $hostId]);
                if (!$this->cache->exists('decodePass')) {
                    $this->cache->set('decodePass', $this->decode($this->host->hostpass));
                }
                $this->connect();
            }
        }

        $this->hostList = Hosting::find()->select('*')->all();
        return parent::beforeAction($action);

    }

    private function connect()
    {
        return $this->sftp->connect(
            $this->host->hostip,
            $this->host->hostuser,
            $this->cache->get('decodePass')
        );
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'hostlist' => $this->hostList
        ]);
    }

    /**
     * @return string
     */
    public function actionExecute()
    {
        $command = $this->request->get('command');
        if (!empty($command))
            return Json::encode([
                'user' => "{$this->host->hostuser}@{$this->host->hostip}:~",
                'response' => $this->sftp->createCommand($command),
                'dir' => $this->sftp->pwd(),
                'hash' => rand(1, 10000),
                'time' => sprintf('%0.5f', Yii::getLogger()->getElapsedTime())
            ]);
    }

    public function actionGet()
    {
        $params = $this->request->get();
        if (!empty($params['path']) && $this->sftp->is_file($params['path'])) {
            $contentFile = $this->sftp->get($params['path'], false);
            if (is_string($contentFile)) {
                return Json::encode([
                    'contentFile' => $contentFile,
                    'mode' => $params['extension'],
                    'time' => sprintf('%0.5f', Yii::getLogger()->getElapsedTime())
                ]);
            }
            return false;
        }
    }

    /**
     * @return string
     */
    public function actionMove()
    {
        $dir = $this->request->get('dir');
        if ($dir == './')
            $absolute_path = $this->sftp->pwd();
        else $absolute_path = $dir;
        $this->sftp->chdir($absolute_path);
        $currentDir = $this->sftp->pwd();
        if ($this->sftp->is_dir($currentDir)) {
            $list = $this->sftp->scanDir();
            return Json::encode([
                'list' => $list,
                'dir' => $currentDir,
                'time' => sprintf('%0.5f', Yii::getLogger()->getElapsedTime())
            ]);
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionAddHost()
    {

        if ($this->request->isPost) {
            $model = new Hosting();
            if ($model->load($this->request->post()) && $model->validate())
                $model->save();
        }

        return $this->redirect('/');
    }

    public function actionRemoveHost($id)
    {
        $model = Hosting::findOne($id);
        if (!empty($model))
            return $model->delete();
    }

}