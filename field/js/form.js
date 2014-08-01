/**
 * Форма редактирования прав для сущности
 */
var fieldForm = function( fieldForm )
{
    fieldButtonsInit();

    s('#btnAddField', fieldForm).tinyboxAjax({
        html : 'html',
        renderedHandler: function(response, tb){
            s('.field_edit_form').ajaxSubmit(function(response){
                s('.item-list').html(response.fields);
                tb._close();
                fieldButtonsInit();
            });
        }
    })
};

s('form.field-list').pageInit( fieldForm );

function fieldButtonsInit() {

    s('a.delete-field-button').each(function(obj) {
        obj.ajaxClick(function(response) {
            s('.item-list').html(response.fields);
            fieldButtonsInit();
        });
    });

    s('a.edit-field-button').tinyboxAjax({
        html : 'html',
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
