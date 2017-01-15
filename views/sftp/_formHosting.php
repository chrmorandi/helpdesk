<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Hosting */
/* @var $form ActiveForm */
?>

<div class="formHosting">
    <?php $form = ActiveForm::begin([
            'action'=>\yii\helpers\Url::toRoute('sftp/add-host'),
            'options' => [
                'data-pjax' => true
            ]
        ]); ?>

        <?= $form->field($model, 'hostip') ?>
        <?= $form->field($model, 'hostpass') ?>
        <?= $form->field($model, 'hostuser') ?>
        <?= $form->field($model, 'hostname') ?>
        <?= $form->field($model, 'public_key')->textarea() ?>
    
        <div class="form-group">
            <?= Html::submitButton('add', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
<div class="clearfix"></div>
</div><!-- _formHosting -->
