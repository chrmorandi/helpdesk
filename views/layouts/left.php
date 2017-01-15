<?php
use \kartik\typeahead\Typeahead;
use yii\helpers\Url;
use yii\web\JsExpression;
$template = '<a class="get-mail" data-target="{{uid}}" href="#mail/{{uid}}">{{subject}}</a>';
?>
<aside style="width: 17%;" class="main-sidebar">
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/avatar.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Anton Pankov</p>
            </div>
        </div>


        <form id="search-form" action="<?= Url::to(['mail/search']) ?>" method="get">
        <?=Typeahead::widget([
            'name' => 'q',
            'options' => ['placeholder' => 'search in Gmail ...'],
            'scrollable' => true,
            'pluginOptions' => ['highlight'=>true],
            'dataset' => [
                [
                    'display' => 'value',
                    'limit'=>40,
                    'remote' => [
                        'url' => Url::to(['mail/search']) . '?q=%QUERY',
                        'wildcard' => '%QUERY'
                    ],
                    'templates' => [
                        'suggestion' => new JsExpression("Handlebars.compile('{$template}')")
                    ]
                ]
            ]
        ]);?>
        <span class="search-bt input-group-btn">
           <button type='submit' name="fullsearch" value="true" id='search-btn' class="btn btn-flat">
               <i class="fa fa-search"></i>
           </button>
        </span>
        </form>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'SFTP Manager', 'icon' => 'fa fa-file-code-o', 'url' => ['/']],
                    ['label' => 'Gmail client',
                        'icon' => 'fa fa-folder','options'=>['class' =>'mails'],'url' => \yii\helpers\Url::toRoute(['mail/view', 'folder_name'=>'all'])],
                    ['label' => 'Phone', 'icon' => 'fa fa-phone-square', 'url' => \yii\helpers\Url::toRoute('/phone')],
                ],
            ]
        ) ?>

    </section>

</aside>
