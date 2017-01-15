<?php


namespace app\commons;

use app\commands\SocketServer;
use app\models\FolderMail;
use app\models\Mail;
use app\models\TextMail;
use Yii;
use roopz\imap\Mailbox;

class MailWorcker
{
    /**
     * @var $criteria
     *    ALL - return all mails matching the rest of the criteria
     *    ANSWERED - match mails with the \\ANSWERED flag set
     *    BCC "string" - match mails with "string" in the Bcc: field
     *    BEFORE "date" - match mails with Date: before "date"
     *    BODY "string" - match mails with "string" in the body of the mail
     *    CC "string" - match mails with "string" in the Cc: field
     *    DELETED - match deleted mails
     *    FLAGGED - match mails with the \\FLAGGED (sometimes referred to as Important or Urgent) flag set
     *    FROM "string" - match mails with "string" in the From: field
     *    KEYWORD "string" - match mails with "string" as a keyword
     *    NEW - match new mails
     *    OLD - match old mails
     *    ON "date" - match mails with Date: matching "date"
     *    RECENT - match mails with the \\RECENT flag set
     *    SEEN - match mails that have been read (the \\SEEN flag is set)
     *    SINCE "date" - match mails with Date: after "date"
     *    SUBJECT "string" - match mails with "string" in the Subject:
     *    TEXT "string" - match mails with text "string"
     *    TO "string" - match mails with "string" in the To:
     *    UNANSWERED - match mails that have not been answered
     *    UNDELETED - match mails that are not deleted
     *    UNFLAGGED - match mails that are not flagged
     *    UNKEYWORD "string" - match mails that do not have the keyword "string"
     *    UNSEEN - match mails which have not been read yet
     *
     */
    private $criteria = 'UNSEEN';


    /**
     * @var Mailbox $mailbox
     */
    public $mailbox;


    /**
     * @var array $mails
     */
    public $mails;


    /**
     * MailWorcker constructor.
     */
    public function __construct()
    {
        $this->mailbox = Yii::$app->imap->connection;
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }


    public function synchronize()
    {
        $ids = $this->mailbox->searchMailbox($this->criteria);
        if (!empty($ids)) {
            $this->mails = $this->mailbox->getMailsInfo($ids);
            if ($this->saveHeadersMails() && $this->downloadMails()){
                $pushedData = (new MailNotifier())->pushNewMails();
                if (!empty($pushedData)){
                    SocketServer::sendData(array_merge(['topic_id' => 'mails'], $pushedData));
                }
            }

        }
    }

    private function saveHeadersMails()
    {
        foreach ($this->mails as $mail) {
            $newMail = new Mail();
            $oldMail = $newMail->findOne(['uid' => $mail->uid]);
            $mail = (array)$mail;


            if (!empty($oldMail)) {
                $oldMail->setAttributes($mail);
                $oldMail->save();
                continue;
            }


            $newMail->setAttributes($mail);
            if ($newMail->from == Yii::$app->params['adminEmail'] ||
                $newMail->from == Yii::$app->params['adminEmailAndName']
            )
                $newMail->folder_id = FolderMail::FOLDER_ALL;

            $newMail->save();
        }
        return true;
    }

    private function downloadMails()
    {
        foreach ($this->mails as $mail) {
            $model = new TextMail();

            if (!$this->mailbox->getMailsInfo([$mail->uid]))
                continue;


            if ($model::findOne(['mail_uid' => $mail->uid])) {
                $this->mailbox->markMailsAsRead([$mail->uid]);
                continue;
            }

            $attributes = $this->mailbox->getMail($mail->uid);
            $model->attach = (array)$attributes->getAttachments();


            $model->setAttributes([
                'mail_uid' => $mail->uid,
                'textHtml' => $attributes->replaceInternalLinks('/attach')
                              ?? $attributes->textPlain,
            ]);

            if ($model->save() && $model->validate())
                unset($model);

        }
        return true;
    }
}