<?php
namespace app\commands;


use app\commons\MailWorcker;
use yii\base\Exception;
use yii\console\Controller;
use Yii;

class MailController extends Controller
{


    public function actionJob()
    {
        $worcker = new MailWorcker();
        try {
            while ($worcker->mailbox->getImapStream()) {
                   $worcker->synchronize();
            }
        } catch (Exception $e) {
            Yii::info($e, 'gmail');
            $this->runAction('job');
        }

    }

}