
<div id="sftp">
    <div class="form-group">
        <label class="pull-right" style="float: left" for="host">Host</label>
        <div class="clearfix"></div>
        <select class="form-control" name="host" id="host">
            <?php foreach ($hostList as $val): ?>
                <option value="<?= $val->id ?>"><?= $val->hostname ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="buttons">
        <div class="btn btn-success move"><i class="fa fa-search-plus" aria-hidden="true"></i> Browse</div>
        <div id="clearConsole" class="btn btn-warning">Clear Console</div>
        <div id="consoleShow" class="btn console"><i class="fa fa-terminal" aria-hidden="true"></i>
            Console
        </div>
        <div id="removeHost" class="btn btn-danger">
            Remove Host
            <i style="width: 20px" class="fa fa-close c-close" aria-hidden="true"></i>
        </div>
        <?php \yii\bootstrap\Modal::begin([
            'id' => 'hosting-modal',
            'header' => 'Add new host',
            'size' => 'modal-md',
            'closeButton' => ['tag' => 'button', 'label' => '&times;'],
            'toggleButton' => [
                'label' => 'Add new host',
                'data-target' => '#hosting-modal',
                'class' => 'btn btn-success',
            ],
            'clientOptions' => false,
        ]); ?>

        <?= $this->render('_formHosting',[
            'model'=> new \app\models\Hosting()
        ]) ?>

        <?php \yii\bootstrap\Modal::end(); ?>
    </div>
    <div class="clearfix"></div>

    <div style="display: none" id="client" class="clent">
        <div class="drag-console">dragged
            <div class="pull-right"><i style="width: 20px" class="fa fa-close c-close" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div id="browse">

    </div>

    <?= $this->render('//tpl/_console') ?>

    <?= $this->render('//tpl/_browser') ?>

    <script>
        window.onload = function () {
            app.sftp.init();
        }
    </script>
</div>
