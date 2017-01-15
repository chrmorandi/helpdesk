<?php

namespace app\models;

use app\commons\Security;
use Yii;

/**
 * This is the model class for table "hosting".
 *
 * @property integer $id
 * @property string $hostip
 * @property string $hostpass
 * @property string $hostuser
 * @property string $public_key
 */
class Hosting extends \yii\db\ActiveRecord
{
    use Security;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hosting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hostip', 'hostpass', 'hostuser', 'hostname'], 'required'],
            [['hostip', 'hostuser', 'hostname'], 'string', 'max' => 30],
            [['hostpass', 'public_key'], 'string', 'max' => 255],
            [['hostpass'], 'unique'],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->hostpass = $this->encode($this->hostpass);
        if (!empty($this->public_key))
            $this->public_key = $this->encode($this->public_key);
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hostip' => 'ip adress',
            'hostpass' => 'pass',
            'hostuser' => 'user name',
        ];
    }
}
