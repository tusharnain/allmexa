<div class="row mb-3 <?= $groupClass ?? '' ?>"">
    <label class=" col-sm-2 col-form-label">
    <?= $label ?>
    </label>
    <div class="col-sm-10">

        <select class="form-select <?= $class ?? '' ?>" name="<?= $name ?>" <?= isset($id) ? "id=\"$id\"" : '' ?>
            <?= $bool_attributes ?? '' ?>>

            <?php if (isset($empty_option)): ?>
                <option value="" <?= isset($disable_empty_option) ? 'disabled' : '' ?>>
                    <?= $empty_option ?>
                </option>
            <?php endif; ?>


            <?php if (isset($options) and is_countable($options)): ?>

                <?php foreach ($options as $title => &$value): ?>

                    <?php if (isset($onlyValues)): ?>
                        <option value="<?= $value ?>" <?= (isset($select) and $select == $value) ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php else: ?>
                        <option value="<?= $value ?>" <?= (isset($select) and $select == $value) ? 'selected' : '' ?>>
                            <?= $title ?>
                        </option>
                    <?php endif; ?>


                <?php endforeach; ?>

            <?php endif; ?>


        </select>
        <div class="invalid-feedback">
        </div>

    </div>
</div>