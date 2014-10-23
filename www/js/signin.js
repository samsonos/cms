/**
 * Created by nazarenko on 23.10.2014.
 */

s('form.form-signin').pageInit(function(form){
    if(form.hasClass('errorAuth')){
        var marginLeft = parseInt(form.css('margin-left'));
        //alert(marginLeft);
        //form.css('margin-left', marginLeft+'px');
        //form.css('margin-right', '0px');
        form.animate(100, 'margin-left', '400');
    }

});