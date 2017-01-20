<?php

namespace app\controllers;

use app\models\AttachmentMail;
use app\models\FolderMail;
use app\models\Mail;
use app\models\UploadFile;
use Yii;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;


class MailController extends AppController
{


    /**
     * @var mixed $mails
     */
    public $mails;


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                '*' => ['get'],
                'upload' => ['post'],
                'send' => ['post']
            ],
        ];

        return $behaviors;

    }

    public function actionView()
    {
        $this->mails = Mail::getData($this->getQueryParams());
        if (!empty($this->mails))
            return $this->renderMailData();
        return false;
    }

    public function renderMailData()
    {
        $provider = new ArrayDataProvider([
            'allModels' => $this->mails['data'],
            'pagination' => [
                'pageSize' => 25
            ],
        ]);

        return $this->render('mail', [
            'mails' => $provider,
            'folders' => $this->cache->get('folders'),
            'currentFolder' => $this->getCurrentFolder(),
            'dbStatus' => Yii::getLogger()->getDbProfiling()
        ]);
    }

    public function getCurrentFolder()
    {
        return $this->request->get('folder_name') ?? 'all';
    }

    public function getQueryParams()
    {
        $params = [];
        $filter = $this->request->get('only');
        $current_folder = $this->getCurrentFolder();
        $params['folder_id'] = FolderMail::getFolderId($current_folder);

        if (!is_null($filter)) {
            switch ($filter) {
                case 'seen':
                    $params['seen'] = '1';
                    break;
                case 'unseen':
                    $params['seen'] = '0';
                    break;
                case 'marker':
                    $params['marker'] = '1';
                    break;
                case 'unmarker':
                    $params['marker'] = '0';
                    break;
            }
        }

        return $params;
    }

    public function actionSearch($q = null)
    {
        if (is_null($q))
            return $this->redirect('/mail/view/all');

        $query = new Query;
        $qString = explode(' ', $q);

        if (!$this->request->get('fullsearch')) {
            $query->select('subject, uid')->distinct()
                ->from('mail')
                ->where('subject LIKE "%' . $q . '%"');

            if (count($qString) > 1) {
                foreach ($qString as $val)
                    if (!empty($val)) $query->where('subject LIKE "%' . $val . '%"');
            }

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out = [];
            foreach ($data as $d) {
                $out[] = [
                    'subject' => $d['subject'],
                    'uid' => $d['uid']
                ];
            }
            echo Json::encode($out);
        } else {
            $this->mails = ArrayHelper::index($query
                ->select('*')->from('mail m')
                ->andFilterWhere([
                    'or',
                    ['like', 'm.subject', $q],
                    ['like', 'm.from', $q]
                ])
                ->orderBy(['id' => SORT_DESC])
                ->createCommand()->queryAll(), 'uid');
            return $this->renderMailData();

        }
        return false;
    }

    public function actionSeen($uid)
    {
        $mail = Mail::findOne(['uid' => $uid]);
        $mail->setAttributes([
            'seen' => 1
        ]);

        if ($mail->validate()) {
            return $mail->save();
        }

        return false;
    }

    public function actionGet($uid)
    {
        $mail = new Mail();
        $content = $mail->getAll($uid);
        if (!empty($content))
            return Json::encode($content);

        return false;
    }

    public function actionDeletefile($uid = null, $fileName = null)
    {
        if (!empty($fileName)) {
            $file = new AttachmentMail();
            $file->deleteFile($fileName);
        } elseif (!empty($uid)) {
            $model = AttachmentMail::findOne(['mail_uid' => $uid]);
            if (!empty($model) && $model->deleteFile()) {
                return true;
            }
        }
        return false;
    }

    public function actionDeletemail($uid)
    {
        return Mail::deleteOne($uid);
    }


    public function actionSend()
    {
        $data = $this->request->post();
        $mail = \Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($data['to'])
            ->setSubject($data['subject'])
            ->setTextBody($data['text']);
        return $mail->send();
    }


    public function actionUpload()
    {
        if ($this->request->isAjax) {
            $fileModel = new UploadFile();
            $fileModel->file = UploadedFile::getInstance($fileModel, 'file');
            if (!empty($fileModel->file))
                $fileModel->upload();
        }
    }

}