<div>
    <?php
    $f = new \ModStart\Field\Audio($field['name'], [$field['title']]);
    if ($field['isRequired']) {
        $f->required();
    }
    $f->server(modstart_web_url('member_data/file_manager/audio'));
    $f->renderMode(\ModStart\Field\Type\FieldRenderMode::FORM);
    $f->value($record?$record[$field['name']]:null);
    echo $f->render();
    ?>
</div>
