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
                tb._close();
                fieldButtonsInit();
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
});

function initFieldIcons() {
    s('.control.delete').each(function(obj) {
        obj.ajaxClick(function(response) {

        }, function() {
            return confirm("Вы уверены, что хотите безвозвратно удалить поле?");
        });
    });
}
