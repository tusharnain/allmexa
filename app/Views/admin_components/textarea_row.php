<div class="row mb-3 <?= $groupClass ?? '' ?>"">
    <label class=" col-sm-2 col-form-label">
    <?= $label ?>
    </label>
    <div class="col-sm-10">

        <textarea class="form-control <?= $class ?? '' ?>" name="<?= $name ?>" rows="5"
            placeholder="<?= $placeholder ?? '' ?>" <?= isset($id) ? "id=\"$id\"" : '' ?> <?= $bool_attributes ?? '' ?>><?= $value ?? '' ?></textarea>

        <div class="invalid-feedback">
        </div>
    </div>
</div>