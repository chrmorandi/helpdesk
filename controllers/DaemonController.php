<?php

namespace app\controllers;

use keltstr\simplehtmldom\SimpleHTMLDom as Html;
use Curl\Curl;
use yii\web\Controller;

class DaemonController extends Controller
{
    public function actionStatusDaemons()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $curl->setOpt(CURLOPT_USERPWD, "apolon13:barbarian92");
        $list = $curl->get("http://127.0.0.1:9001/");
        $html = null;

        if (!empty($list))
            $html = Html::str_get_html($list);

        return json_encode(['list'=>$html->find('form',0)->innertext]);
    }
}