<?php

namespace app\commons;

use app\models\Mail;
use app\commands\SocketServer;
use app\models\Notification;

class MailNotifier extends Notifier
{

    private $mailNotified = null;


    private $urls = [
        "all" => "http://helpdesk/mails",
        "one" => "http://helpdesk/#mail/"
    ];


    public function pushNewMails()
    {
        $this->mailNotified = Mail::getData(['seen'=>'0']);

        if (is_null($this->mailNotified))
            return false;

        $notification = new Notification();
        $notified = $this->mailNotified['data'];
        $notification->setAttributes([
            "text" => sprintf("new (%d) messages...", count($notified)),
            "url"  => (count($notified) > 1) ? $this->urls['all']
                     : $this->urls['one'] . array_shift($notified)['uid']
        ]);

        if ($this->pushNotification($notification))
            return $this->mailNotified;

        return false;

    }

}