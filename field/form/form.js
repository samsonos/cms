/**
 * Форма редактирования прав для сущности
 */
var fieldForm = function( fieldForm )
{
    fieldButtonsInit();

    s('#btnAddField', fieldForm).tinyboxAjax({
        html : 'html',
        darkBackground: false,
        renderedHandler: function(response, tb) {
            s('.field_edit_form').ajaxSubmit(function(response){
                s('.item-list').html(response.fields);
                tb._close();
                fieldButtonsInit();
            });
        }
    });
};

//s('form.field-list').pageInit( fieldForm );

function fieldButtonsInit() {

    s('a.delete-field-button').each(function(obj) {
        obj.ajaxClick(function(response) {
            s('.item-list').html(response.fields);
            fieldButtonsInit();
        }, function() {
            return confirm("Вы уверены, что хотите безвозвратно удалить связь этого поля со структурой?");
        });
    });

    s('a.edit-field-button').tinyboxAjax({
        html: 'html',
        darkBackground: false,
        renderedHandler: function(response, tb){
            s('.field_edit_form').ajaxSubmit(function(response) {
                s('.item-list').html(response.fields);
                fieldButtonsInit();
                tb._close();
            });
        },
        beforeHandler: function() {
            return true;
        }
    });
}
s(document).pageInit(function() {
    initFieldIcons();
    fieldForm();
    s('.sub_menu_a').ajaxClick(function(response) {

    });
});

function initFieldIcons() {
    s('.control.delete').each(function(obj) {
        obj.ajaxClick(function(response) {
            s('.material-content').html(response.table);
            initFieldIcons()
        }, function() {
            return confirm("Вы уверены, что хотите безвозвратно удалить поле?");
        });
    });

    s('.control.edit').each(function(obj) {
        obj.tinyboxAjax({
            html: 'html',
            renderedHandler: function(response, tb){
                s('.field_edit_form').ajaxSubmit(function(response) {
                    s('.material-content').html(response.table);
                    initFieldIcons();
                    tb._close();
                });
            },
            beforeHandler: function() {
                return true;
            }
        });
    });

    s('.field_pager a').each(function(obj) {
        obj.ajaxClick(function(response) {
        s('.material-content').html(response.table);
        s('.field_pager').html('<li>Страница:</li>' + response.pager);
        initFieldIcons();
        });
    });
}