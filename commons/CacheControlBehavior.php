<?php


namespace app\commons;

use app\controllers\AppController;
use app\controllers\SftpController;
use app\models\Mail;
use yii\base\Behavior;
use yii\caching\Cache;
use yii\caching\TagDependency;


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
            Mail::EVENT_AFTER_DELETE => 'flushAll',
            Mail::EVENT_AFTER_UPDATE => 'flushAll',
            Mail::EVENT_AFTER_INSERT => 'flushAll',
            AppController::EVENT_BEFORE_ACTION => 'set'
        ];
    }

    public function flushAll()
    {
        $this->cache->flush();
    }

    public function set()
    {
        foreach ($this->elementCache as $key => $element) {

            if (!$this->cache->get($key)) {
                if (is_callable($element['value']))
                    $value = call_user_func($element['value']);
                else $value = $element['value'];

                $dependency = $element['dependency'];
                if (!empty($dependency))
                    $depend = new $dependency['class']($dependency['config']);

                $this->cache->set($key, $value, $element['duration'], $depend ?? null);
            }
        }
    }


}