<div class="form-group mb-3">
    <label class="form-label">
        <?= $label ?? '' ?>
    </label>
    <div class="input-group">
        <input class="form-control <?= $class ?? '' ?>" type="<?= $type ?? 'text' ?>" name="<?= $name ?>"
            placeholder="<?= $placeholder ?? '' ?>" value="<?= $value ?>" <?= isset($id) ? " id=\"$id\" " : '' ?>
            <?= $bool_attributes ?? '' ?>>
        <a class="input-group-text" role="button" onclick="copyText(event,'<?= $value ?>', true);">
            <i class="fa-solid fa-clipboard" checked-class="fa-solid fa-clipboard-check"></i>
        </a>
    </div>
</div>