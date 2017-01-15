<?php
namespace app\commons;

use yii\base\Exception;
use Curl\Curl;
use yii\base\Model;

class Notifier
{
    /**
     * @var Model $notification
     */
    public $notification;


    /**
     * @return bool
     * @throws Exception
     */
    private function push() : bool
    {
        $attr = $this->notification->attributes;
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $exec = $curl->post('https://pushall.ru/api.php', $attr);

        if (!empty($exec->error))
            throw new Exception("Error: " . $exec->error);

        return true;
    }

    /**
     * @param Model $notification
     * @return Notifier|bool
     * @throws Exception
     */
    public function pushNotification(Model $notification)
    {
        $this->notification = $notification;

        if(!($this->notification instanceof Model))
            throw new Exception("The object must be an instance Model");

        if($this->notification->validate())
           return $this->push();

        return false;

    }

}