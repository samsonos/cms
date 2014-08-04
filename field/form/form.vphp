<div class="field_edit_tinybox">
    <!-- НАЧАЛО шаблона формы полей элемента структуры сайта -->
    <form method="post"
          class="form2 field_edit_form"
          action="<?php url_base('field/save', 'cmsnav_id', 'field_id', 'field/renderfields', 'cmsnav_id')?>"
    >
        <div class="close-button" title="Закрыть форму"><input type="button" value="Х" class=""></div>
        <input type="hidden" name="FieldID" id="FieldID" value="<?php iv('field_id'); ?>">
        <?php if( !isv('field_id')):?>
            <h2 class="header">Добавление поля для:<br> <span class="bold"><?php iv('cmsnav_Name')?></span></h2>
        <?php else:?>
            <h2 class="header">Редактирование поля <span class="bold"><?php iv('field_Name'); ?></span></h2>
        <?php endif?>
        <input type="hidden" name="StructureID" id="StructureID" value="<?php iv('cmsnav_id') ?>">
        <ul class="table-like-list">
            <li title="Название поля"><label>Наименование:</label><div class="input-text"><input name="Name" id="Name" placeholder="Введите наименование поля для категории..." value="<?php iv('field_Name'); ?>"></div></li>
            <li title="Описание поля"><label>Описание:</label><div class="input-text"><input name="Description" id="Description" placeholder="Введите описание поля для категории..." value="<?php iv('field_Description'); ?>"></div></li>
            <li title="Описание поля"><label>Значение:</label><textarea name="Value" id="Value"><?php iv('field_Value'); ?></textarea></li>
            <li title="Тип поля"><label>Тип:</label><?php v('type_select');?></li>
            <?php if( !isv('field_id')):?>
                <li title="Добавить новое поле" class="right-align"><input type="submit" value="Добавить" class=""></li>
            <?php else:?>
                <li title="Сохранить поле" class="right-align"><input type="submit" value="Сохранить" class=""></li>
            <?php endif?>
        </ul>
    </form>
    <!-- КОНЕЦ шаблона формы полей элемента структуры сайта -->
</div>