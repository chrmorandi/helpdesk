<?php


namespace app\commons;

use app\controllers\AppController;
use app\models\Mail;
use yii\base\Behavior;
use yii\caching\Cache;


class CacheControlBehavior extends Behavior
{
    /**
     * @var Cache $cache
     */
    public $cache;


    /**
     * @var array $elementCache
     */
    public $elementCache = [];


    public function init()
    {
        $this->cache = \Yii::$app->cache;
        parent::init();
    }

    public function events()
    {
        return [
            Mail::EVENT_AFTER_DELETE => 'flush',
            Mail::EVENT_AFTER_UPDATE => 'flush',
            Mail::EVENT_AFTER_INSERT => 'flush',
            AppController::EVENT_BEFORE_ACTION => 'set'
        ];
    }

    public function flush(){
        $this->cache->flush();
        echo 'cache-clear';
    }

    public function set(){
        foreach ($this->elementCache as $key => $element) {
            if (!$this->cache->get($key)) {
                if (is_callable($element['value']))
                    $value = call_user_func($element['value']);
                else
                    $value = $element['value'];

                $this->cache->set($key, $value, $element['duration'], $element['dependency']);
            }
        }
    }



}