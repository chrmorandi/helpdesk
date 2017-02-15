<?php
/**
 * Created by PhpStorm.
 * User: apolon13
 * Date: 24.01.17
 * Time: 23:59
 */

namespace app\models;


use yii\base\Model;

class RemoteElement extends Model
{

    public $rights = 700;

    public $name;

    public $type;

    public $path;

    public function rules()
    {
        return [
            [['name', 'type', 'path'], 'string', 'max' => 50],
            [['rights'], 'integer'],
            [['rights', 'name', 'path'], 'required']
        ];
    }
}