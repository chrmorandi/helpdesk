<?php

namespace app\models;
use yii\web\UploadedFile;

use yii\base\Model;

class UploadFile extends Model
{

    /**
     * @var UploadedFile $file
     */

    public $file;

    public static $folder = 'attach/';

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function upload() : bool
    {
        if ($this->validate()) {
            $this->file->saveAs($this->getFullPath());
            return true;
        }
        return false;

    }

    public function getName() : string
    {
        return $this->file->baseName. '.' . $this->file->extension;
    }

    protected function getFullPath() : string
    {
        return self::$folder . $this->getName();
    }

    public static function getFolerName() : string
    {
        return self::$folder;
    }


}