<?php

namespace app\commons;

use Yii;

trait Security
{
    public static function encode($data)
    {
        return Yii::$app->getSecurity()->encryptByKey(
            $data,
            Yii::$app->request->cookieValidationKey
        );
    }

    public static function decode($data)
    {
        return Yii::$app->getSecurity()->decryptByKey(
            $data,
            Yii::$app->request->cookieValidationKey
        );
    }
}