<?php

namespace app\controllers;

use app\commons\CacheControlBehavior;
use app\models\FolderMail;
use app\models\Mail;
use yii\caching\Cache;
use yii\web\Controller;
use Yii;
use yii\web\Request;


class AppController extends Controller
{

    /**
     * @var Cache $cache
     */
    public $cache;

    /**
     * @var Request $request
     */
    public $request;



    public function init()
    {
        $this->cache = Yii::$app->cache;
        $this->request = Yii::$app->request;
        $this->view->params['folders'] = $this->cache->get('folders');
        $this->view->params['unseenMails'] = $this->cache->get('unseenMails');
        parent::init();
    }

    public function behaviors()
    {
        return [
            'cacheBehavior' => [
                'class' => CacheControlBehavior::className(),
                'elementCache' => [
                    'folders' => [
                        'value' => function () {
                            return FolderMail::find()->all();
                        }
                    ],
                    'unseenMails' => [
                        'value' => function () {
                            return Mail::getData(['seen' => '0']);
                        }
                    ]
                ]
            ]
        ];
    }


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }


    public function actionClearCache()
    {
        if ($this->cache->flush())
            echo 'cache-clear';
    }

}
