<?= $this->registerJsFile('https://cloud9ide.github.io/emmet-core/emmet.js');?>
<?= \wbraganca\AceEditor\AceEditorWidget::widget([
    'id' => 'editor',
    'name'=>'content',
    'value' => $content ?? "<?php \r\n",
    'extensions' => [
        'beautify',
        'language_tools'
    ],
    'mode'=> $mode ?? 'php',
    'theme'=>'monokai',
    'autocompletion'=> true,
    'aceOptions' => [
        'showPrintMargin' => false,
        'minLines' => 1,
        'maxLines' => 10000,
        'newLineMode' => 'unix',
    ]
]);
?>