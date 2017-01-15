<div data-target="<?= $model['uid'] ?>"
     class="<?php if ($model['seen'] == '0') echo 'unseen' ?> mail-box mass box box-success">
    <div style="left: 10px;top: 0;" title="Отметить как важное"
         class="important">

    </div>
    <input name="Mail[uid]" value="<?= $model['uid'] ?>" style="margin: 10px"
           class="pull-left" type="checkbox"/>

    <a data-num="<?= $model['seen'] ?>" data-target="<?= $model['uid'] ?>"
       style="color: inherit" class="get" href="#mail/<?= $model['uid'] ?>">
        <div class="container">
            <h5 class="from"><?= $model['from'] ?></h5>
            <h5 class="subj"><?= $model['subject'] . '...' ?? 'Empty Subject...' ?>
                <p class="date"><?= Yii::$app->formatter->asDate($model['udate'], 'php:d F Y') ?></p>
            </h5>
        </div>
        <div class="clearfix"></div>
    </a>
    <div class="clearfix"></div>
</div>


