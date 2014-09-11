var loader = new Loader(s('body'));

s(document).pageInit(function() {
    initUserTable();
    s('#createNewUser').tinyboxAjax({
        html:'html',
        renderedHandler: function(response, tb) {
            s(".form2").ajaxSubmit(function(response) {
                tb._close();
                s('#content').html(response.table);
                initUserTable();
            });
        },
        beforeHandler: function() {
            loader.show('Загрузка формы', true);
            return true;
        },
        responseHandler: function() {
            loader.hide();
            return true;
        }
    });
});

function initUserTable() {
    s('.btnEditUser').tinyboxAjax({
        html:'html',
        renderedHandler: function(response, tb) {
            s(".form2").ajaxSubmit(function(response) {
                loader.hide();
                tb._close();
                s('#content').html(response.table);
                initUserTable();
            }, function() {
                loader.show(true);
                return true;
            });
        },
        beforeHandler: function() {
            loader.show('Загрузка формы', true);
            return true;
        },
        responseHandler: function() {
            loader.hide();
            return true;
        }
    });

    s('.btnDeleteUser').ajaxClick(function(response) {
        s('#content').html(response.table);
        initUserTable();
    }, function() {
        return confirm('Delete?');
    });
}