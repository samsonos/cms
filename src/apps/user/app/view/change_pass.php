<div class="income_form">
    <a href="#" class="close-button close" title="<?php t('Закрыть форму')?>"></a>
    <br />
    <h1><?php t('Смена пароля')?></h1>
    <form action="user/change_pass" method="POST">
    <p><?php t('Старый пароль')?></p>
    <p class="field"><input type="text" name="Old_Pass" class="user"/></p>
    <p><?php t('Новый пароль')?></p>
    <p class="field"><input type="text" name="New_Pass" class="user"/></p>
    <p><?php t('Еще раз новый пароль')?></p>
    <p class="field"><input type="text" name="New_Pass2" class="user"/></p>
    <p><input type="submit" class="reg_button2" value=""/></p>
    </form>
     
</div>