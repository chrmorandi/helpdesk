<?php
use yii\helpers\Html;

app\assets\AppAsset::register($this);
dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title>Help Desk</title>
    <?php $this->head() ?>
</head>
<body class="<?= \dmstr\helpers\AdminLteHelper::skinClass() ?>">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        'header.php', [
            'directoryAsset' => $directoryAsset,
            'folders' => $this->params['folders'],
            'mails' => $this->params['unseenMails']
        ]
    ) ?>

    <?= $this->render(
        'left.php',
        ['directoryAsset' => $directoryAsset]
    )
    ?>

    <?= $this->render(
        'content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

    <footer style="width: 83%;float: right" class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy;2016 <a href="https://vk.com/id54563405">Anton
                Pankov</a>.</strong> All rights
        reserved.
    </footer>
</div>
<?php $this->endBody() ?>
<?php $this->registerJsFile('/js/views/EditorView.js', ['position' => yii\web\View::POS_END]); ?>
</body>
</html>
<?php $this->endPage() ?>

