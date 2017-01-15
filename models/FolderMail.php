<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "folders_mail".
 *
 * @property integer $id
 * @property string $folder_name
 */
class FolderMail extends \yii\db\ActiveRecord
{

    const FOLDER_ALL = 1;

    const FOLDER_IMPORTANT = 2;

    const FOLDER_TRASH = 3;

    const FOLDER_SENT = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'folder_mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['folder_name'], 'required'],
            [['folder_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'folder_name' => 'Имя папки',
        ];
    }

    public static function getFolderId($folder_name){
        return self::findOne(['folder_name'=>$folder_name])->id;
    }

    public function getCountMails($folder_id)
    {
        return Mail::find()->where(['folder_id'=>$folder_id])->count();
    }
}
