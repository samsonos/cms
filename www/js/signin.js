/**
 * Created by nazarenko on 23.10.2014.
 */

s('form.errorAuth').pageInit(function(){
    var cont = s('.container');
    cont.css('left', '20px');
    cont.animate(40, 'left', '25', function(){
        cont.animate(0, 'left', '50', function(){
            cont.animate(40, 'left', '50', function(){
                cont.animate(0, 'left', '50', function(){
                    cont.animate(20, 'left', '25');
                });
            });
        });
    });
});
