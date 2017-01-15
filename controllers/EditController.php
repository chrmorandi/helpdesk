<?php

namespace app\controllers;

use Yii;

class EditController extends \yii\web\Controller
{

    public function actionEval()

    {
        if (Yii::$app->request->isAjax) {
            $eval = Yii::$app->request->post();
            $code = str_replace(['<?php', '<?', '?>'], false, $eval['code']);
            echo eval($code);
        }
        return false;

    }

}
