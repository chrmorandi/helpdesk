<?php
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
<div id="mail-wrap">
    <?php Pjax::begin(); ?>
    <div class="mail-content" >
        <div class="blog-masthead">
            <div style="width: 100%;padding: 5px;" class="container">
                <nav class="blog-nav">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <button href="#" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select <b class="caret"></b></button>
                            <ul class="dropdown-menu">
                                <li><a href="">All</a></li>
                                <li><a href="">Lesen</a></li>
                                <li><a href="">Ungelesen</a></li>
                                <li><a href="">Marked</a></li>
                                <li><a href="">Unmarked</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <button href="#" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Flag as <b class="caret"></b></button>
                            <ul class="dropdown-menu">
                                <li><a href="">Read</a></li>
                                <li><a href="">Unread</a></li>
                                <li><a href="">Marked</a></li>
                                <li><a href="">Unmarked</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <button href="#" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Move selected in <b class="caret"></b></button>
                            <ul class="dropdown-menu">
                                <?php foreach($folders as $folder):?>
                                    <li><a href="<?=Url::to(['/mail/view','folder_name'=>$folder['folder_name']]) ?>"><?=  $folder['folder_name']?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <button href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Show<b class="caret"></b></button>
                            <ul class="dropdown-menu">
                                <li><a href="<?=Url::to(['/mail/view','folder_name'=>$currentFolder]) ?>">All</a></li>
                                <li><a href="<?=Url::to(['/mail/view','folder_name'=>$currentFolder,'only'=>'seen']) ?>">Read</a></li>
                                <li><a href="<?=Url::to(['/mail/view','folder_name'=>$currentFolder,'only'=>'unseen']) ?>">Unread</a></li>
                                <li><a href="<?=Url::to(['/mail/view','folder_name'=>$currentFolder,'only'=>'marker']) ?>">Marked</a></li>
                                <li><a href="<?=Url::to(['/mail/view','folder_name'=>$currentFolder,'only'=>'unmarker']) ?>">Unmarked</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <button href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Go to folder <b class="caret"></b></button>
                            <ul class="dropdown-menu">
                                <?php foreach($folders as $folder):?>
                                    <li><a href="<?=Url::to(['mail/view','folder_name'=>$folder['folder_name']]) ?>"><?=  $folder['folder_name']?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php
        echo ListView::widget([
            'dataProvider' => $mails,
            'itemView' => '_list',
            'emptyText'=> 'Писем нет.',
            'pager' => [
                'lastPageLabel' => '»»',
                'firstPageLabel' => '««',
                'maxButtonCount' => 4,
            ],
            'emptyTextOptions' => [
                'tag' => 'p',
                'class' => 'empty-mails'
            ],
            'summary' => '<div class="summ-mail">Показано {begin} - {end} из {totalCount}</div>',
        ]);

        ?>
    </div>
        <?php Pjax::end(); ?>
</div>
<script>
    window.onload = function () {
        new MailManager;
    }
</script>





