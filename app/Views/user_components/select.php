<div class="form-group mb-3 <?= $groupClass ?? '' ?>">
    <label class="form-label">
        <?= $label ?>
    </label>
    <div class="input-group">
        <?php if (isset($icon)) : ?>
            <a class="input-group-text bg-primary text-white">
                <i class="<?= $icon ?>"></i>
            </a>
        <?php endif; ?>
        <select class="form-select <?= $class ?? '' ?>" name="<?= $name ?>" <?= isset($id) ? "id=\"$id\"" : '' ?> <?= (isset($disabled) && $disabled) ? ' disabled ' : '' ?> <?= $bool_attributes ?? '' ?> <?= $other_attr ?? '' ?>>


            <?php if (isset($empty_option)) : ?>
                <option value="" <?= isset($disable_empty_option) ? 'disabled' : '' ?> <?= isset($select_empty_option) ? 'selected' : '' ?>>
                    <?= $empty_option ?>
                </option>
            <?php endif; ?>

            <?php if (isset($options) and is_countable($options)) : ?>

                <?php foreach ($options as $title => &$value) : ?>

                    <?php if (isset($onlyValues)) : ?>
                        <option value="<?= $value ?>" <?= (isset($select) and $select == $value) ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php else : ?>
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