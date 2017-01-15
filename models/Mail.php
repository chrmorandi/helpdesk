<?php

namespace app\models;

use app\commons\CacheControlBehavior;
use Ratchet\Wamp\Exception;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use yii\widgets\FragmentCache;

/**
 * This is the model class for table "mail".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $from
 * @property integer $size
 * @property string $subject
 * @property integer $msgno
 * @property integer $udate
 * @property string $message_id
 * @property string $in_reply_to
 * @property integer $folder_id
 * @property integer $seen
 * @property integer $marker
 *
 */
class Mail extends \yii\db\ActiveRecord
{

    public $mail;

    public function behaviors()
    {
        return[
            'cacheBehavior' => [
                'class' => CacheControlBehavior::className()
            ]
        ];
    }

    public static function tableName()
    {
        return 'mail';
    }

    public static function getData(array $prop) : array
    {
        $query =  Yii::$app->db->cache(function ($db) use ($prop) {
            return [
                'data' => ArrayHelper::index(Mail::find()
                    ->where($prop)
                    ->orderBy(['id' => SORT_DESC])
                    ->asArray()->all(), 'uid'),
                'count' => Mail::find()
                    ->where($prop)->count()
            ];

        }, 60 * 10);
        return $query;
    }

    public static function deleteOne(int $uid){
        return Mail::deleteAll(['uid' => $uid]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'message_id'], 'required'],
            [['uid', 'size', 'msgno', 'udate', 'seen', 'folder_id', 'marker'], 'integer'],
            [['subject'], 'string'],
            [['from', 'message_id', 'in_reply_to'], 'string', 'max' => 255],
        ];
    }


    public function getAll(int $uid, bool $history = true)
    {
        $this->mail = Mail::find()->joinWith([
            'text_mail',
            'attachment_mail'
        ])->where(['mail.uid' => $uid])->asArray()->one();

        if ($history === true && !empty($this->mail)) {
            $this->getParentReplys($this->mail['in_reply_to'])->sortByUnix('parentReplys');
            $this->getChildReplys($this->mail['message_id'])->sortByUnix('childReplys');
        }

        return $this->mail;

    }

    private function getParentReplys(string $in_reply_to = null) : self
    {
        $reply = $this->findMail(['message_id' => $in_reply_to]);
        $next = $reply->in_reply_to;
        if (!empty($reply)) {
            $this->mail['parentReplys'][$reply->uid] = $reply;
            if ($next) $this->getParentReplys($next);
        }

        return $this;
    }

    public function sortByUnix($key){
        if(!empty($this->mail[$key])){
            usort($this->mail[$key], function ($a, $b){
                if ($a['udate'] == $b['udate']) {
                    return 0;
                }
                return ($a['udate'] < $b['udate']) ? -1 : 1;
            });
        }
    }

    private function getChildReplys(string $massage_id = null) : self
    {
        $reply = $this->findMail(['in_reply_to' => $massage_id]);
        if (!empty($reply)) {
            $this->mail['childReplys'][$reply->uid] = $reply;
            $this->getChildReplys($reply->message_id);
        }

        return $this;
    }

    public function findMail(array $param)
    {
        return Mail::find()->where($param)->one();
    }


    public function getAttachment_mail()
    {
        return $this->hasMany(AttachmentMail::className(), ['mail_uid' => 'uid']);
    }

    public function getText_mail()
    {
        return $this->hasOne(TextMail::className(), ['mail_uid' => 'uid']);
    }

}
