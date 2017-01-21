<?php

namespace app\models;

use Yii;
use yii\base\ExitException;
use yii\db\Exception;

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
        $files = AttachmentMail::find()->where(['mail_uid' => $uid])->all();
        if (!empty($files)) {
            foreach ($files as $file)
                $this->deleteFile($file->fileName);
        }
    }

    public function deleteFile()
    {
        if (!$this->fileName)
            throw new Exception('file not found');

        $path = $this->getPathFile();
        if (file_exists($path) && $path) {
            unlink($path);
            return AttachmentMail::delete();
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
