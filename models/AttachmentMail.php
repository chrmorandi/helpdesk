<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attachment_mail".
 * @property integer $id
 * @property integer $mail_uid
 * @property string $fileName
 */
class AttachmentMail extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'attachment_mail';
    }

    public function deleteAllFiles(int $uid)
    {
        $files = AttachmentMail::find()->where(['mail_uid' => $uid])->asArray()->all();
        if (!empty($files)) {
            foreach ($files as $val)
                $this->deleteFile($val['fileName']);
        }
    }

    public function deleteFile(string $fileName = null) : bool
    {
        if (!empty($fileName))
            $this->fileName = $fileName;

        if (file_exists($this->getPathFile())) {
            unlink($this->getPathFile());
            AttachmentMail::delete();
            return true;
        }

        return false;

    }

    public function getPathFile() : string
    {
        return UploadFile::getFolerName() . $this->fileName;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fileName'], 'string', 'max' => 255],
            [['mail_uid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'File Name',
            'mail_uid' => 'uid'
        ];
    }
}
