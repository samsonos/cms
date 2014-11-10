/**
 * Created by Olexandr Nazarenko on 23.10.2014.
 */

s('.main-form').pageInit(function(form){
    var preloader = s('.preloader_container');
    var button = s('button.btn-signin');
    form.ajaxSubmit(function(response){
        preloader.show();
        button.hide();
        if(response.status == '0'){
            s('div.container','body#signin').html();
            s('div.container','body#signin').html(response['html']);
            formSubm();
            formShake();
            preloader.hide();
            button.show();
        } else {
            preloader.hide();
            button.show();
            document.location.href = "../";
        }
    });
});

/**
 * Assignment Ajax event
 */
function formSubm(){
    var form = s('.main-form');
    var preloader = s('.preloader_container');
    var button = s('button.btn-signin');
    form.ajaxSubmit(function(response){
        preloader.show();
        button.hide();
        if(response.status == '0'){
            s('div.container','body#signin').html();
            s('div.container','body#signin').html(response['html']);
            formSubm();
            formShake();
            preloader.hide();
            button.show();
        } else {
            preloader.hide();
            button.show();
            document.location.href = "../";
        }
    });
}

/**
 * Form animation with invalid authorization
 */
function formShake(){
    var container = s('.form-container');
    container.css('left', '20px');
    container.animate(40, 'left', '50', function(){
        container.animate(0, 'left', '25', function(){
            container.animate(40, 'left', '25', function(){
                container.animate(0, 'left', '25', function(){
                    container.animate(20, 'left', '50');
                });
            });
        });
    });
}
