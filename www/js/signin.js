/**
 * Created by nazarenko on 23.10.2014.
 */

s('.main-form').pageInit(function(form){
    form.ajaxSubmit(function(response){
        if(response.status == '0'){
            s('div.container','body#signin').html();
            s('div.container','body#signin').html(response['html']);
            formSubm();
            formShake();
        } else {
            document.location.href = "/";
        }
    });
});

// Функция присваивания форме ajax отправки данных
function formSubm(){
    var form = s('.main-form');
    form.ajaxSubmit(function(response){
        if(response.status == '0'){
            s('div.container','body#signin').html();
            s('div.container','body#signin').html(response['html']);
            formSubm();
            formShake();
        } else {
            document.location.href = "/";
        }
    });
}

// Функция анимация формы при неверной авторизации
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
