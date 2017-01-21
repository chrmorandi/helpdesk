<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "mailInfo".
 *
 * @property integer $id
 * @property string $textHtml
 * @property integer $mail_uid
 */
class TextMail extends ActiveRecord
{
    public $attach = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text_mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail_uid'], 'required'],
            [['textHtml'], 'default', 'value' => "Empty text"],
            [['mail_uid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'textHtml' => 'Text Html',
            'mail_uid' => 'Mail Uid',
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            foreach ($this->attach as $item) {
                $model = new AttachmentMail();
                $model->setAttributes([
                    'fileName' => $item->filename,
                    'mail_uid' => $this->mail_uid
                ]);
                if ($model->validate())
                    $model->save();

            }
            return true;
        }
        return false;
    }
}
