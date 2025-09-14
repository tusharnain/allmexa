<div class="form-group mb-3">
    <label class="form-label">
        <?= $label ?? '' ?>
    </label>
    <div class="input-group" <?= isset($id) ? "id=\"$id\"" : '' ?>>
        <input class="form-control <?= $class ?? '' ?>" type="<?= $type ?? 'text' ?>" name="<?= $name ?>"
            placeholder="<?= $placeholder ?? '' ?>" value="<?= $value ?>" <?= $bool_attributes ?? '' ?>>
        <a class="input-group-text bg-white text-muted" onclick="copyText(event,'<?= $value ?>', true);">
            <i class="mdi mdi-clipboard-outline" checked-class="mdi mdi-clipboard-check"></i>
        </a>
    </div>
</div>