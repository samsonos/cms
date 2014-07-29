/**
 * Created by p.onysko on 03.03.14.
 */
function CMSNavigationFormInit() {

    // Флаг нажатия на кнопку управления
    var ControlFormOpened = false;

    // Указатель на текущий набор кнопок управления
    var ControlElement = null;
    /**
    * обработчик добавления новой записи
    */
    s(".control.add").tinyboxAjax({
        html:'html',
        renderedHandler: function(response, tb) {
            /** автоматический транслит Урл*/
            s("#Name").keyup(function(obj) {
                s.trace(s("#Url").val());
                s("#Url").val(s("#Name").translit());
            });
            /** транслит по кнопке */
            s("#generateUrl").click(function(obj) {
                s.trace(s("#Url").val());
                if (confirm("Вы точно хотите сгенерировать адрес?")) {
                    s("#Url").val(s("#Name").translit());
                }
            });
            s(".form2").ajaxSubmit(function(response) {
                s(".tree-container").html(response.tree).treeview();
                tb.close();
                s( '.structure-element' )
                    .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
                    .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
                CMSNavigationFormInit();
            });
            s(".cancel-button").click(function() {
                tb.close();
            });
            //CMSNavigationFormInit();
        }
    });
    /**
     * обработчик редактирование новой записи
     */
    s(".control.edit").tinyboxAjax({
        html:'html',
        renderedHandler: function(response, tb) {
            s("#generateUrl").click(function(obj) {
                s.trace(s("#Url").val());
                if (confirm("Вы точно хотите сгенерировать адрес?")) {
                    s("#Url").val(s("#Name").translit());
                }
            });
            s(".form2").ajaxSubmit(function(response) {
                s(".tree-container").html(response.tree).treeview();
                tb.close();
                s( '.structure-element' )
                    .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
                    .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
                CMSNavigationFormInit();
            });
            s(".cancel-button").click(function() {
                tb.close();
            });
        }
    });
    /**
     * обработка удаления
     */
    s(".control.delete").ajaxClick(function(response) {
        s(".tree-container").html(response.tree).treeview();
        s( '.structure-element' )
            .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
            .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
        CMSNavigationFormInit();
    }, function() {
        if (confirm("Вы уверены, что хотите безвозвратно удалить структуру?")) {
            return true;
        } else {
            return false;
        }
    });

    s('.control.fields').tinyboxAjax({
        html : 'html',
        renderedHandler: function(response, tb){
            fieldForm(tb);
        }
    });

    /**
     * обработка изменения позиции элемента в дереве
     */
    s(".control.move-up").ajaxClick(function(response) {
        s(".tree-container").html(response.tree).treeview();
        s( '.structure-element' )
            .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
            .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
        CMSNavigationFormInit();
    });
    s(".control.move-down").ajaxClick(function(response) {
        s(".tree-container").html(response.tree).treeview();
        s( '.structure-element' )
            .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
            .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
        CMSNavigationFormInit();
    });
    /**
     * обработчик для кнопки "верхнего" меню (sub_menu)
     */
    s("#newSSE").tinyboxAjax({
        html:'html',
        renderedHandler: function(response, tb) {
            /** автоматический транслит Урл*/
            s("#Name").keyup(function(obj) {
                s.trace(s("#Url").val());
                s("#Url").val(s("#Name").translit());
            });
            /** транслит по кнопке */
            s("#generateUrl").click(function(obj) {
                s.trace(s("#Url").val());
                if (confirm("Вы точно хотите сгенерировать адрес?")) {
                    s("#Url").val(s("#Name").translit());
                }
            });
            s(".form2").ajaxSubmit(function(response) {
                s(".tree-container").html(response.tree).treeview();
                tb.close();
                s( '.structure-element' )
                    .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
                    .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
                CMSNavigationFormInit();
            });
            s(".cancel-button").click(function() {
                tb.close();
            });
        }
    });
    s(".open").ajaxClick(function(response) {
        s("#data").html(response.tree).treeview();
        s('.sub_menu').html(response.sub_menu);
        s(".all").removeClass('active');
        s('.structure-element')
            .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
            .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
        CMSNavigationFormInit();
    });
    s(".all").ajaxClick(function(response) {
        s("#data").html(response.tree).treeview();
        s(".all").addClass('active');
        s( '.structure-element' )
            .mouseover( function(el){ if(!ControlFormOpened) { s( '.control-buttons', el ).show(); ControlElement = el; } })
            .mouseout( 	function(el){ if(!ControlFormOpened) s( '.control-buttons', el ).hide(); });
        CMSNavigationFormInit();
    });

}

s('#structure').pageInit(function() {
    CMSNavigationFormInit(); //инициализация событий
});
