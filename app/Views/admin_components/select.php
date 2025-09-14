<div class="form-group mb-3 <?= $groupClass ?? '' ?>"">
    <label class=" form-label">
    <?= $label ?>
    </label>

    <select class="form-select <?= $class ?? '' ?>" name="<?= $name ?>" <?= isset($id) ? "id=\"$id\"" : '' ?>
        <?= $bool_attributes ?? '' ?>>


        <?php if (isset($empty_option)): ?>
            <option value="" <?= isset($disable_empty_option) ? 'disabled' : '' ?> <?= isset($select_empty_option) ? 'selected' : '' ?>>
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