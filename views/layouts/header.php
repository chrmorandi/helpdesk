<?php
use yii\helpers\Html;
?>

<header class="main-header">

    <?= Html::a('<h4><span class="logo-mini">APP</span><span class="logo-lg">HelpDesk</span></h4>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <div class="pull-left folders">

            <?php foreach($folders as $folder):?>
            <a href="<?= \yii\helpers\Url::toRoute(["mail/view","folder_name"=>$folder->folder_name]) ?>">
                <div style="position: relative" class="folder">
                    <i style="font-size: 25px" class="fa fa-folder-open" aria-hidden="true"></i>
                    <p style="float: right;color: #FFF;margin: 3px 0 0 5px"><?= $folder->folder_name ?></p>
                </div>
            </a>
            <?php endforeach; ?>

        </div>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a  onclick="$('#massages').toggle()" class="dropdown-toggle">
                        <span class="label c-gmail label-success"><?= $mails['count'] ?></span>
                        <i class="fa fa-envelope-o" aria-hidden="true"></i> New Mails
                    </a>
                    <ul class="dropdown-menu" id="massages">
                        <li class="header">You have <span
                                class="c-gmail"><?= $mails['count'] ?></span>
                            messages
                        </li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul id="container-massage" class="menu">
                                <?php foreach ($mails['data'] as $mail): ?>
                                    <li class="mass"
                                        title="<?= $mail['subject'] ?? 'Empty Subject' ?>">
                                        <!-- start message -->
                                        <a class="get-mail" data-target="<?= $mail['uid'] ?>"
                                           href="#mail/<?= $mail['uid'] ?>">
                                            <div class="pull-left">
                                                <img src="/images/gmail.png"
                                                     class="img-circle"/>
                                            </div>
                                            <h4 style="float: none">
                                                <?= $mail['from']; ?>
                                            </h4>
                                            <small>
                                                <i class="fa fa-clock-o"></i>
                                                <span
                                                    class="udmass"><?= $mail['udate'] ?></span>
                                            </small>
                                            <p><?= $mail['subject'] ?? 'Empty Subject' ?></p>
                                        </a>
                                        <div title="Скрыть"
                                             class="hide-preview">
                                            <i style="width: 20px"
                                               class="fa fa-close"
                                               aria-hidden="true"></i>
                                        </div>
                                        <div title="Отметить как важное" class="important">

                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                <!-- end message -->
                            </ul>
                        </li>
                        <li class="footer mails"><a href="<?= \yii\helpers\Url::toRoute(['mails', 'folder_name'=>'all']) ?>">See All
                                Messages</a></li>
                    </ul>
                </li>
                <li class="dropdown open-editor">
                    <a  class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-edit" aria-hidden="true"></i> Editor
                    </a>
                </li>
                <li>
                    <a onclick="app.ajax('/app/clear-cache'); return false" href="<?= \yii\helpers\Url::toRoute(['cache-clear']) ?>">
                        <i class="fa fa-cogs" aria-hidden="true"></i> Clear-cache
                    </a>
                </li>
                <li>
                    <a class="status-daemons" href="<?= \yii\helpers\Url::toRoute(['/daemon/status-daemons']) ?>">
                        <i class="fa fa-info" aria-hidden="true"></i> Status-daemons
                    </a>

                </li>
            </ul>
        </div>
    </nav>
    <div class="daemons-control">
        <div onclick="$('.daemons-control').hide()" class="st close-daemons"><i class="fa fa-close" aria-hidden="true"></i> Close</div>
        <div class="st update-daemons"><i class="fa fa-refresh" aria-hidden="true"></i> Update</div>
        <div class="list"></div>
    </div>
</header>

<div id="container-edit" class="mail-container control-sidebar-bg">

</div>

<div class="control-editor">
    <div class="close-edit btn btn-danger"><i class="fa fa-close" aria-hidden="true"></i> Close</div>
    <div disabled class="save-edit btn btn-success"><i class="fa fa-save" aria-hidden="true"></i> Save</div>
    <div class="run-edit btn btn-warning"><i class="fa fa-play" aria-hidden="true"></i> Run</div>
    <div disabled class="hide-result btn btn-warning"><i class="fa fa-close" aria-hidden="true"></i> Hide result</div>
</div>

<div id="editor" class="control-sidebar-bg">

    <?= $this->render('//app/editor') ?>

</div>
<pre class="eval-code">

</pre>

<div class="errorModal modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="errorModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content error-message">

        </div>
    </div>
</div>

<?= $this->render('//tpl/_mail') ?>
<?= $this->render('//tpl/_prewievMail') ?>
