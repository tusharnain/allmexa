<?php

$replace = "<a href=\"$link\">$linkOn</a>";
$title = str_replace($linkOn, $replace, $title);

if (!isset($name))
    $name = 'tnc';

?>



<div class="form-group mb-0">
    <div class="checkbox p-0">
        <input name="<?= $name ?>" id="tnccheck_<?= $name ?>" type="checkbox">
        <label class="text-muted" for="tnccheck_<?= $name ?>">
            <?= $title ?>
        </label>
        <div class="invalid-feedback"></div>
    </div>
</div>